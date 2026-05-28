<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Models\Product; 
use App\Services\OpenFoodService; 
class ProductController extends Controller { 
    // LISTAR PRODUTOS 
    public function webIndex() {
         $products = Product::latest()->get();
          return view( 'products.index',
           compact('products') ); } 
    
    // FORMULÁRIO 
    public function create() {
        return view('products.create');
        }
    
    // SALVAR PRODUTO MANUAL
    public function webStore(Request $request) {
        Product::create([ 
            'name' => $request->name,
            'price' => $request->price,
            'stock' => 10, 'is_external' => false 
        ]);
        return redirect('/products'); 
        } 
        
    public function import(OpenFoodService $service) {
        $data = $service->getProduct('7891000100103');

        $product = $data['product'] ?? [];

        Product::create([
            'name' => $product['product_name'] ?? 'Produto',
            'price' => $this->generateSmartPrice($product['product_name'] ?? ''),
            'stock' => 20,
            'barcode' => $product['code'] ?? null,
            'external_source' => 'open_food_facts',
            'is_external' => true,
            'image' => $product['image_url'] ?? null,
        ]);

        return redirect('/products');
    }
    private function generateSmartPrice(string $name): float {
        $name = strtolower($name);

        // preços realistas por categoria
        if (str_contains($name, 'leite')) return 5.99;
        if (str_contains($name, 'arroz')) return 24.90;
        if (str_contains($name, 'feijão')) return 8.99;
        if (str_contains($name, 'café')) return 12.90;
        if (str_contains($name, 'chocolate')) return 9.50;
        if (str_contains($name, 'óleo')) return 7.99;
        if (str_contains($name, 'macarrão')) return 4.99;

        // fallback controlado (não aleatório louco)
        return rand(6, 40) + 0.90;
    }
}