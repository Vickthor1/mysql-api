<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenFoodService
{
    public function getProduct($barcode)
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get(
                    "https://world.openfoodfacts.org/api/v0/product/{$barcode}.json"
                );

            if (!$response->successful()) {
                return [];
            }

            return $response->json() ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
}