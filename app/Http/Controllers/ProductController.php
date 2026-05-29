<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\OpenFoodService;
use App\Services\ProductSearchService;

class ProductController extends Controller
{
    // LISTAR PRODUTOS com busca e filtros
    public function webIndex(Request $request)
    {
        $query    = $request->input('q', '');
        $sort     = $request->input('sort', '');       // price_asc | price_desc
        $origin   = $request->input('origin', '');     // internal | external | ''

        $searchService = app(ProductSearchService::class);

        if ($query !== '') {
            // Busca unificada: banco + API externa
            $products = $searchService->search($query, $origin, $sort);
        } else {
            // Sem busca: apenas produtos do banco com filtros
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

            $products = $dbQuery->get()->map(fn($p) => [
                'name'        => $p->name,
                'price'       => $p->price,
                'image'       => $p->image_url,
                'source'      => $p->is_external ? 'external' : 'local',
                'is_external' => $p->is_external,
            ]);
        }

        return view('products.index', compact('products', 'query', 'sort', 'origin'));
    }

    public function index(Request $request)
    {
        $query  = $request->input('q', '');
        $sort   = $request->input('sort', '');
        $origin = $request->input('origin', '');

        if ($query !== '') {
            $products = app(ProductSearchService::class)->search($query, $origin, $sort);
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

            $products = $dbQuery->get()->map(fn($p) => [
                'name'        => $p->name,
                'price'       => $p->price,
                'image'       => $p->image_url,
                'source'      => $p->is_external ? 'external' : 'local',
                'is_external' => $p->is_external,
            ]);
        }

        return response()->json($products);
    }

    // FORMULÁRIO DE CADASTRO
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
        ]);

        $product = Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'stock'       => 10,
            'is_external' => false,
            'image'       => $request->image ?: null,
        ]);

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect('/products')->with('success', 'Produto cadastrado com sucesso!');
    }

    // SALVAR PRODUTO MANUAL (com suporte a imagem por URL)
    public function webStore(Request $request)
    {
        return $this->store($request);
    }

    // IMPORTAR DA API (Open Food Facts)
    public function import(OpenFoodService $service)
    {
        $data    = $service->getProduct('7891000100103');
        $product = $data['product'] ?? null;

        if (empty($product) || empty($product['code'])) {
            return redirect('/products')->with('error', 'Não foi possível importar o produto da Open Food Facts.');
        }

        Product::create([
            'name'            => $product['product_name'] ?? 'Produto',
            'price'           => $this->generateSmartPrice($product['product_name'] ?? ''),
            'stock'           => 20,
            'barcode'         => $product['code'] ?? null,
            'external_source' => 'open_food_facts',
            'external_id'     => $product['code'] ?? null,
            'is_external'     => true,
            'image'           => $product['image_url'] ?? null,
        ]);

        return redirect('/products')->with('success', 'Produto importado da API!');
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
