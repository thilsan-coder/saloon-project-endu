<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Velvet & Co. Salon') }}</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Vite Scripts & CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-['Plus_Jakarta_Sans',sans-serif] text-stone-800 bg-stone-50 antialiased min-h-full selection:bg-rose-500 selection:text-white relative overflow-x-hidden">
        <!-- Ambient Warm Gradient Backdrop Effects -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute -top-[15%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-rose-200/30 blur-[120px]"></div>
            <div class="absolute top-[35%] -right-[10%] w-[45vw] h-[45vw] rounded-full bg-amber-200/25 blur-[120px]"></div>
            <div class="absolute -bottom-[15%] left-[25%] w-[40vw] h-[40vw] rounded-full bg-rose-100/40 blur-[100px]"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        </script>
    </body>
</html>
