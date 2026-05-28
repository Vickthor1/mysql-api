<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0" > 
    <title>SuperMarket</title> 
    @vite([ 'resources/css/app.css', 'resources/js/app.js' ]) 
</head> 
<body class="bg-gray-100"> 
    <header class="bg-red-600 shadow-lg"> 
        <div class="max-w-7xl mx-auto p-4 flex justify-between"> 

            <h1 class="text-white text-2xl font-bold"> 
            SuperMarket 
            </h1> 
            
            <nav class="flex gap-6 text-white"> 
                    
                <a href="/">
                    Home
                </a> 

                <a href="/products"> 
                    Produtos 
                </a> 
                <a href="/products/create"> 
                    Adicionar 
                </a> 

                <a href="/import-product"> 
                    Importar API 
                </a> 
            </nav> 
        </div> 
    </header> 
<main class="max-w-7xl mx-auto p-6"> 
    @yield('content') 
</main> 
</body> 
</html>