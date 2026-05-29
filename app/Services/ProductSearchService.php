<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductSearchService
{
    public function search(string $query, string $origin = '', string $sort = '')
    {
        $query = trim($query);

        if ($query === '') {
            return collect();
        }

        // 1. BUSCA NO BANCO LOCAL
        $local = collect();
        if ($origin !== 'external') {
            $local = Product::query()
                ->where('name', 'like', "%{$query}%")
                ->limit(12)
                ->get()
                ->map(fn($p) => [
                    'name'        => $p->name,
                    'price'       => $p->price,
                    'image'       => $p->image_url ?? $p->image ?? null,
                    'stock'       => (int) ($p->stock ?? 0),
                    'external_id' => $p->external_id ?? null,
                    'source'      => $p->is_external ? 'external' : 'local',
                    'is_external' => $p->is_external,
                ]);
        }

        // 2. BUSCA NA API EXTERNA
        $external = collect();
        if ($origin !== 'internal') {
            $external = $this->searchOpenFood($query);
        }

        // 3. JUNTA TUDO
        $all = $local->merge($external);

        // 3.1 AGREGAR/REMOVER DUPLICADOS — priorizar `external_id` quando disponível
        $all = $all->groupBy(function ($p) {
            if (!empty($p['external_id'])) {
                return 'ext:' . trim((string) $p['external_id']);
            }
            $name = strtolower(trim($p['name'] ?? ''));
            $img = trim((string)($p['image'] ?? ''));
            return 'nm:' . md5($name . '|' . $img);
        })->map(function ($group) {
            $items = $group->values();
            $name = $items->first()['name'] ?? '';

            // imagem: priorizar a primeira imagem não vazia
            $image = $items->pluck('image')->filter()->first() ?? null;

            // preço: priorizar preço de item local quando existir, senão menor preço disponível
            $local = $items->first(fn($i) => empty($i['is_external']) || $i['is_external'] === false);
            if ($local) {
                $price = $local['price'] ?? null;
                $source = 'local';
                $is_external = false;
            } else {
                $price = $items->pluck('price')->filter()->min() ?? null;
                $source = 'external';
                $is_external = true;
            }

            // estoque: soma de todos os estoques dos itens agrupados
            $stock = $items->pluck('stock')->map(fn($s) => (int) $s)->sum();

            // external_id: priorizar quando existir
            $external_id = $items->pluck('external_id')->filter()->first() ?? null;

            return [
                'name'        => $name,
                'price'       => $price,
                'image'       => $image,
                'stock'       => $stock,
                'external_id' => $external_id,
                'source'      => $source,
                'is_external' => $is_external,
            ];
        })->values();

        // 4. APLICA ORDENAÇÃO
        if ($sort === 'price_asc') {
            $all = $all->sortBy(fn($p) => $p['price'] ?? PHP_INT_MAX)->values();
        } elseif ($sort === 'price_desc') {
            $all = $all->sortByDesc(fn($p) => $p['price'] ?? 0)->values();
        }

        return $all;
    }

    private function searchOpenFood(string $query)
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://world.openfoodfacts.org/cgi/search.pl', [
                    'search_terms'  => $query,
                    'search_simple' => 1,
                    'action'        => 'process',
                    'json'          => 1,
                    'page_size'     => 8,
                ]);

            $data = $response->json();

            if (!isset($data['products'])) {
                return collect();
            }

            return collect($data['products'])
                ->filter(fn($p) => !empty($p['product_name']))
                ->take(8)
                ->map(fn($p) => [
                    'name'        => $p['product_name'] ?? 'Sem nome',
                    'price'       => $this->generateSmartPrice($p['product_name'] ?? ''),
                    'image'       => $p['image_front_small_url'] ?? null,
                    'stock'       => 0,
                    'external_id' => $p['code'] ?? null,
                    'source'      => 'external',
                    'is_external' => true,
                ])
                ->values();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function generateSmartPrice(string $name): float
    {
        $name = strtolower($name);

        if (str_contains($name, 'leite'))     return 5.99;
        if (str_contains($name, 'arroz'))     return 24.90;
        if (str_contains($name, 'feijão'))    return 8.99;
        if (str_contains($name, 'café'))      return 12.90;
        if (str_contains($name, 'chocolate')) return 9.50;
        if (str_contains($name, 'óleo'))      return 7.99;
        if (str_contains($name, 'macarrão'))  return 4.99;

        return rand(6, 40) + 0.90;
    }
}
