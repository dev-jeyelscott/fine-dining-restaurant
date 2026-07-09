@php
    $restaurantName = \App\Models\SiteSetting::value('restaurant_name', config('app.name'));

    $links = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Menu', 'route' => 'menu'],
        ['label' => 'Reservation Request', 'route' => 'reservation-request.create'],
        ['label' => 'Order Inquiry', 'route' => 'order-inquiry.create'],
        ['label' => 'Gallery', 'route' => 'gallery'],
        ['label' => 'Banquet Hall', 'route' => 'banquet-hall'],
        ['label' => 'Contact', 'route' => 'contact.create'],
    ];
@endphp

<header
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-white/10 bg-stone-950/90 backdrop-blur"
>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-amber-200">
            {{ $restaurantName }}
        </a>

        <nav class="hidden items-center gap-6 lg:flex">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    class="text-sm font-medium text-stone-300 transition hover:text-amber-200"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <button
            type="button"
            class="inline-flex items-center rounded-md border border-white/10 px-3 py-2 text-sm text-stone-200 lg:hidden"
            @click="open = !open"
            aria-label="Toggle navigation"
        >
            Menu
        </button>
    </div>

    <nav x-show="open" x-cloak class="border-t border-white/10 px-4 py-4 lg:hidden">
        <div class="flex flex-col gap-3">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    class="text-sm font-medium text-stone-300 transition hover:text-amber-200"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
