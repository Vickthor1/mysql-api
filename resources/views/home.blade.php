@extends('layouts.app')

@section('content')

{{-- HERO --}}
<div class="bg-white rounded-3xl shadow-sm border border-amber-100 overflow-hidden mb-8">
    <div class="flex flex-col md:flex-row items-center gap-8 p-8 md:p-12">
        <div class="flex-1">
            <p class="text-green-600 font-semibold text-sm mb-2 uppercase tracking-widest">Bem-vindo ao</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 leading-tight mb-4">
                Mercadinho<br><span class="text-green-600">do Bairro</span>
            </h1>
            <p class="text-gray-500 mb-6 text-lg">
                Produtos frescos, preços honestos e tudo que a sua casa precisa.
            </p>
            <div class="flex gap-3 flex-wrap">
                <a href="/products"
                   class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-2xl transition shadow-sm">
                    Ver Produtos
                </a>
                <a href="/products/create"
                   class="bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold px-6 py-3 rounded-2xl transition">
                    Cadastrar Produto
                </a>
            </div>
        </div>
        <div class="text-8xl md:text-9xl select-none">🛒</div>
    </div>
</div>

{{-- INFO CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm flex items-center gap-4">
        <span class="text-3xl">🥦</span>
        <div>
            <p class="font-bold text-gray-700">Produtos Frescos</p>
            <p class="text-sm text-gray-400">Cadastro interno do mercado</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm flex items-center gap-4">
        <span class="text-3xl">🌎</span>
        <div>
            <p class="font-bold text-gray-700">API Externa</p>
            <p class="text-sm text-gray-400">Open Food Facts integrado</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm flex items-center gap-4">
        <span class="text-3xl">🔍</span>
        <div>
            <p class="font-bold text-gray-700">Busca Unificada</p>
            <p class="text-sm text-gray-400">Interno + externo na mesma lista</p>
        </div>
    </div>
</div>

@endsection
