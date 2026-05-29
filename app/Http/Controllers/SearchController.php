<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductSearchService;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query  = trim((string) $request->input('q', ''));
        $sort   = (string) $request->input('sort', '');
        $origin = (string) $request->input('origin', '');

        if ($query !== '') {
            $results = app(ProductSearchService::class)->search($query, $origin, $sort);
        } else {
            $dbQuery = Product::query();

            if ($origin === 'internal') {
                $dbQuery->where('is_external', false);
            } elseif ($origin === 'external') {
                $dbQuery->where('is_external', true);
            }

            if ($sort === 'price_asc') {
                $dbQuery->orderBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $dbQuery->orderBy('price', 'desc');
            } else {
                $dbQuery->latest();
            }

            $results = $dbQuery->get()->map(fn($p) => [
                'name'        => $p->name,
                'price'       => $p->price,
                'image'       => $p->image_url,
                'source'      => $p->is_external ? 'external' : 'local',
                'is_external' => $p->is_external,
            ]);
        }

        $results = collect($results)->values();

        return view('products.index', compact('results', 'query', 'sort', 'origin'));
    }
}
