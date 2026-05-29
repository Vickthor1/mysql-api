@extends('layouts.app')

@section('content')

{{-- TÍTULO + BUSCA MOBILE --}}
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Produtos</h2>
    <p class="text-sm text-gray-400">
        @if($query)
            Resultados para <span class="font-semibold text-gray-600">"{{ $query }}"</span>
            &bull; {{ count($products) }} encontrados
        @else
            {{ count($products) }} produtos disponíveis
        @endif
    </p>
</div>

{{-- FILTROS --}}
<form action="/products" method="GET" class="bg-white rounded-2xl border border-amber-100 shadow-sm p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

        {{-- Busca por nome --}}
        <div class="relative flex-1">
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Buscar por nome..."
                class="w-full border border-amber-200 bg-amber-50 rounded-xl px-4 py-2.5 pr-9 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
            >
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Ordenar por preço --}}
        <select name="sort" class="border border-amber-200 bg-amber-50 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-600">
            <option value=""     {{ $sort === ''           ? 'selected' : '' }}>Ordenar por...</option>
            <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Preço: menor → maior</option>
            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Preço: maior → menor</option>
        </select>

        {{-- Filtro de origem --}}
        <select name="origin" class="border border-amber-200 bg-amber-50 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-600">
            <option value=""         {{ $origin === ''         ? 'selected' : '' }}>Todos</option>
            <option value="internal" {{ $origin === 'internal' ? 'selected' : '' }}>🏪 Mercado</option>
            <option value="external" {{ $origin === 'external' ? 'selected' : '' }}>🌎 Externos (API)</option>
        </select>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition whitespace-nowrap">
            Filtrar
        </button>

        @if($query || $sort || $origin)
        <a href="/products" class="text-sm text-gray-400 hover:text-red-500 flex items-center gap-1 whitespace-nowrap transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Limpar
        </a>
        @endif
    </div>
</form>

{{-- LISTA DE PRODUTOS --}}
@if(count($products) === 0)
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">🔍</div>
        <p class="font-semibold text-lg">Nenhum produto encontrado</p>
        <p class="text-sm mt-1">Tente outro termo ou limpe os filtros</p>
        <a href="/products/create" class="inline-block mt-6 bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
            + Cadastrar produto
        </a>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($products as $product)
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden flex flex-col">

            {{-- IMAGEM --}}
            <div class="h-40 bg-amber-50 flex items-center justify-center overflow-hidden relative">
                @if(!empty($product['image']))
                    <img
                        src="{{ $product['image'] }}"
                        alt="{{ $product['name'] }}"
                        class="h-full w-full object-contain p-3"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="hidden h-full w-full items-center justify-center text-4xl">🛒</div>
                @else
                    <span class="text-5xl select-none">
                        @php
                            $name = strtolower($product['name']);
                            if (str_contains($name, 'leite'))      echo '🥛';
                            elseif (str_contains($name, 'café'))   echo '☕';
                            elseif (str_contains($name, 'arroz'))  echo '🍚';
                            elseif (str_contains($name, 'pão'))    echo '🍞';
                            elseif (str_contains($name, 'carne'))  echo '🥩';
                            elseif (str_contains($name, 'frango')) echo '🍗';
                            elseif (str_contains($name, 'ovo'))    echo '🥚';
                            elseif (str_contains($name, 'fruta'))  echo '🍎';
                            elseif (str_contains($name, 'suco'))   echo '🧃';
                            elseif (str_contains($name, 'água'))   echo '💧';
                            else                                   echo '🛒';
                        @endphp
                    </span>
                @endif

                {{-- TAG DE ORIGEM --}}
                <div class="absolute top-2 left-2">
                    @if(($product['source'] ?? '') === 'external' || ($product['is_external'] ?? false))
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium border border-blue-200">
                            🌎 Externo
                        </span>
                    @else
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium border border-green-200">
                            🏪 Mercado
                        </span>
                    @endif
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="p-4 flex flex-col flex-1">
                <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 flex-1">
                    {{ $product['name'] }}
                </h3>

                <div class="mt-3">
                    @if(!empty($product['price']))
                        <p class="text-green-700 font-extrabold text-xl">
                            R$ {{ number_format($product['price'], 2, ',', '.') }}
                        </p>
                    @else
                        <p class="text-gray-400 text-sm italic">Preço não disponível</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
