<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductSearchService
{
    public function search(string $query, string $origin = '', string $sort = '')
    {
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
                    'image'       => $p->image_url,
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
                    'price'       => null,
                    'image'       => $p['image_front_small_url'] ?? null,
                    'source'      => 'external',
                    'is_external' => true,
                ])
                ->values();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
