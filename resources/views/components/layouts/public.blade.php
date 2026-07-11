<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-public.seo-meta
        :title="$title ?? null"
        :description="$description ?? null"
    />

    @vite(['resources/css/public.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-brand-ink font-sans text-brand-ivory antialiased">
    <div class="min-h-screen">
        <x-public.header />

        <main>
            {{ $slot }}
        </main>

        <x-public.footer />
    </div>
</body>
</html>
