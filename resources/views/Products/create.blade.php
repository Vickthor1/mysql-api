@extends('layouts.app') 

@section('content') 

<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-xl"> 
    <form action="/products" method="POST"> 
        @csrf 
        <input type="text" name="name" placeholder="Nome" class="w-full border p-4 rounded-2xl mb-5" > 
        <input type="number" step="0.01" name="price" placeholder="Preço" class="w-full border p-4 rounded-2xl mb-5" > 
        <button class="bg-red-600 text-white px-8 py-4 rounded-2xl" > 
            Salvar 
        </button> 
    </form> 
</div> 
@endsection