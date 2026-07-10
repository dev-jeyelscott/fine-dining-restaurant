<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-public.seo-meta
        :title="$title ?? null"
        :description="$description ?? null"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-950 text-stone-100 antialiased">
    <div class="min-h-screen">
        <x-public.header />

        <main>
            {{ $slot }}
        </main>

        <x-public.footer />
    </div>
</body>
</html>
