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
    <a
        href="#main-content"
        class="fixed left-4 top-4 z-[100] -translate-y-24 rounded-md bg-amber-300 px-4 py-2 font-semibold text-stone-950 transition focus:translate-y-0 focus:outline-none focus:ring-2 focus:ring-white"
    >
        Skip to main content
    </a>

    <div class="min-h-screen">
        <x-public.header />

        <main id="main-content" tabindex="-1">
            {{ $slot }}
        </main>

        <x-public.footer />
    </div>
</body>
</html>
