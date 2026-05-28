<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductSearchService
{
    public function search(string $query)
    {
        // 1. BUSCA NO BANCO LOCAL
        $local = Product::query()
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'price' => $p->price,
                    'image' => $p->image,
                    'source' => 'local',
                ];
            });

        // 2. BUSCA NA API EXTERNA
        $external = $this->searchOpenFood($query);

        // 3. JUNTA TUDO
        return $local->merge($external);
    }

    private function searchOpenFood(string $query)
    {
        $response = Http::withoutVerifying()
            ->get("https://world.openfoodfacts.org/cgi/search.pl", [
                'search_terms' => $query,
                'search_simple' => 1,
                'action' => 'process',
                'json' => 1,
            ]);

        $data = $response->json();

        if (!isset($data['products'])) {
            return collect();
        }

        return collect($data['products'])->take(10)->map(function ($p) {
            return [
                'name' => $p['product_name'] ?? 'Sem nome',
                'price' => null,
                'image' => $p['image_front_small_url'] ?? null,
                'source' => 'external',
            ];
        });
    }
}