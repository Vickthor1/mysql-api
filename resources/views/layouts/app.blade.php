<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercadinho do Bairro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-amber-50 min-h-screen">

    {{-- HEADER --}}
    <header class="bg-white border-b border-amber-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

            {{-- LOGO --}}
            <a href="/" class="flex items-center gap-2 shrink-0">
                <span class="text-2xl">🛒</span>
                <div class="leading-tight">
                    <span class="font-extrabold text-green-700 text-lg tracking-tight">Mercadinho</span>
                    <span class="block text-xs text-amber-600 font-medium -mt-1">do Bairro</span>
                </div>
            </a>

            {{-- BUSCA RÁPIDA --}}
            <form action="/products" method="GET" class="flex-1 max-w-md hidden sm:flex">
                <div class="relative w-full">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Buscar produtos..."
                        class="w-full border border-amber-200 bg-amber-50 rounded-full px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                    >
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- NAV --}}
            <nav class="flex items-center gap-1 text-sm shrink-0">
                <a href="/" class="px-3 py-1.5 rounded-full text-gray-600 hover:bg-amber-100 hover:text-green-700 transition font-medium">
                    Início
                </a>
                <a href="/products" class="px-3 py-1.5 rounded-full text-gray-600 hover:bg-amber-100 hover:text-green-700 transition font-medium">
                    Produtos
                </a>
                <a href="/products/create" class="px-3 py-1.5 rounded-full bg-green-600 text-white hover:bg-green-700 transition font-medium ml-1">
                    + Adicionar
                </a>
                <a href="/api-info" class="px-3 py-1.5 rounded-full border border-amber-300 text-amber-700 hover:bg-amber-100 transition font-medium ml-1" title="Sobre a API">
                    📦 API
                </a>
            </nav>
        </div>
    </header>

    {{-- FLASH --}}
    @if(session('success'))
    <div class="max-w-6xl mx-auto px-4 pt-4">
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-6xl mx-auto px-4 pt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
    </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="border-t border-amber-100 bg-white mt-12 py-6 text-center text-sm text-gray-400">
        🛒 Mercadinho do Bairro &bull; Laravel + MySQL + Tailwind
    </footer>

</body>
</html>
