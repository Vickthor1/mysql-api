<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenFoodService
{
    public function getProduct($barcode)
    {
        $response = Http::withoutVerifying()
            ->get(
                "https://world.openfoodfacts.org/api/v0/product/{$barcode}.json"
            );

        return $response->json();
    }
}