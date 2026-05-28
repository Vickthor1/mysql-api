@extends('layouts.app') 

@section('content') 

<h2 class="text-4xl font-bold mb-8"> 
    Produtos 

</h2> 
            <div class="grid grid-cols-4 gap-6"> 
                @foreach($products as $product) 
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition overflow-hidden flex flex-col">
                    @if($product->image)
                        <img
                            src="{{ $product->image }}"
                            class="h-full w-full object-contain p-4"
                        >
                    @else
                        <div class="text-gray-400 text-sm">
                            Sem imagem
                        </div>
                    @endif
                <div class="p-5"> 
                <h3 class="text-xl font-bold"> 
                    {{ $product->name }} 
                </h3> 
                <p class="text-red-600 text-2xl font-bold mt-3"> 
                    R$ {{ $product->price }} 
                </p> 
                @if($product->is_external) 
                <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full"> 
                    Produto Externo 
                </span> 
                @else 
                <span class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded-full"> 
                    Produto do Mercado 
                </span> 
                @endif 
            </div> 
        </div> 
    @endforeach 
</div> 
@endsection