@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-3xl shadow-sm border border-amber-100 p-8">
        <div class="flex flex-col gap-4">
            <div>
                <h1 class="text-3xl font-bold text-green-800">Como funciona a API</h1>
                <p class="mt-3 text-gray-600 max-w-2xl">
                    Esta aplicação usa uma API interna para buscar produtos e uma integração externa com o Open Food Facts.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5">
                    <h2 class="text-xl font-semibold text-green-700">Pesquisa de produtos</h2>
                    <p class="mt-2 text-sm text-gray-700 leading-relaxed">
                        A busca de produtos está disponível em <code class="bg-white px-2 py-1 rounded text-xs">/products?q=...</code>. Você pode filtrar por origem usando <code class="bg-white px-2 py-1 rounded text-xs">origin=internal</code> ou <code class="bg-white px-2 py-1 rounded text-xs">origin=external</code>.
                    </p>
                </div>

                <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5">
                    <h2 class="text-xl font-semibold text-green-700">Importação externa</h2>
                    <p class="mt-2 text-sm text-gray-700 leading-relaxed">
                        O botão "API" leva a esta página de explicação. A página de importação real está em <code class="bg-white px-2 py-1 rounded text-xs">/import-product</code> e busca dados do Open Food Facts para importar produtos externos.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5">
                <h2 class="text-xl font-semibold text-green-700">O que está disponível</h2>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-2 mt-2">
                    <li>Busca de produtos por nome</li>
                    <li>Filtro de origem interna ou externa</li>
                    <li>Importação de produtos externos via Open Food Facts</li>
                    <li>Exibição de preço, estoque, imagem e detalhes</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="/products" class="inline-flex items-center justify-center rounded-full bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
                    Ver produtos
                </a>
                <a href="/import-product" class="inline-flex items-center justify-center rounded-full border border-amber-300 px-5 py-3 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition">
                    Ir para importação
                </a>
            </div>
        </div>
    </div>
@endsection
