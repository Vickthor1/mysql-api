@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="/products" class="text-sm text-gray-400 hover:text-green-600 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar para produtos
        </a>
        <h2 class="text-2xl font-extrabold text-gray-800 mt-3">Cadastrar Produto</h2>
        <p class="text-sm text-gray-400 mt-1">Adicione um produto interno do mercado</p>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 md:p-8">
        <form action="/products" method="POST">
            @csrf

            {{-- Nome --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nome do produto *</label>
                <input
                    type="text"
                    name="name"
                    placeholder="Ex: Leite Integral 1L"
                    required
                    class="w-full border border-amber-200 bg-amber-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                >
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preço --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Preço (R$) *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">R$</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="price"
                        placeholder="0,00"
                        required
                        class="w-full border border-amber-200 bg-amber-50 rounded-xl px-4 py-3 pl-9 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                    >
                </div>
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- URL da imagem --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    URL da imagem
                    <span class="font-normal text-gray-400 ml-1">(opcional)</span>
                </label>
                <input
                    type="url"
                    name="image"
                    placeholder="https://exemplo.com/imagem.jpg"
                    class="w-full border border-amber-200 bg-amber-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                >
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Cole a URL de uma imagem do produto (jpg, png, webp)</p>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition shadow-sm text-sm">
                ✅ Salvar Produto
            </button>
        </form>
    </div>

    {{-- DICA API --}}
    <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
        <span class="text-xl mt-0.5">📦</span>
        <div>
            <p class="text-sm font-semibold text-blue-700">Quer importar da API?</p>
            <p class="text-xs text-blue-500 mt-0.5">
                Use o botão <strong>API</strong> no menu para importar produtos do Open Food Facts automaticamente.
            </p>
        </div>
    </div>
</div>

@endsection
