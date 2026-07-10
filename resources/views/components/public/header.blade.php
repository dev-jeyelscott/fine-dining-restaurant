@php
    $restaurantName = $settings['restaurant_name'] ?? config('app.name');
    $isHome = request()->routeIs('home');

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
    x-data="{ open: false, scrolled: @js(! $isHome) }"
    @if ($isHome)
        @scroll.window="scrolled = window.scrollY > 40"
    @endif
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    @keydown.escape.window="open = false"
    :class="(scrolled || open)
        ? 'border-white/10 bg-brand-ink/95 shadow-[0_18px_50px_rgba(0,0,0,0.22)] backdrop-blur-md'
        : 'border-transparent bg-transparent'"
    class="{{ $isHome ? 'fixed' : 'sticky bg-brand-ink/95' }} inset-x-0 top-0 z-50 border-b transition duration-500"
>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 sm:px-6 lg:px-10">
        <a
            href="{{ route('home') }}"
            class="group inline-flex min-w-0 items-center gap-2 font-display text-xl tracking-[0.12em] text-brand-ivory sm:text-2xl"
            aria-label="{{ $restaurantName }} home"
        >
            <span class="truncate">{{ $restaurantName }}</span>
            <span
                class="size-1.5 shrink-0 rounded-full bg-brand-gold transition duration-300 group-hover:scale-150"
                aria-hidden="true"
            ></span>
        </a>

        <nav class="hidden items-center gap-4 xl:flex" aria-label="Primary navigation">
            @foreach ($links as $link)
                @php
                    $isActive = request()->routeIs($link['route']);
                @endphp

                <a
                    href="{{ route($link['route']) }}"
                    @class([
                        'whitespace-nowrap text-[0.68rem] font-semibold uppercase tracking-[0.16em] transition duration-300',
                        'text-brand-gold' => $isActive,
                        'text-stone-200 hover:text-brand-gold' => ! $isActive,
                    ])
                    @if ($isActive) aria-current="page" @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <button
            type="button"
            class="inline-flex size-11 items-center justify-center border border-white/20 text-brand-ivory transition hover:border-brand-gold hover:text-brand-gold xl:hidden"
            @click="open = ! open"
            :aria-expanded="open.toString()"
            aria-controls="mobile-navigation"
            aria-label="Toggle navigation"
        >
            <svg
                x-show="! open"
                class="size-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.75"
                aria-hidden="true"
            >
                <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>

            <svg
                x-show="open"
                x-cloak
                class="size-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.75"
                aria-hidden="true"
            >
                <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <nav
        id="mobile-navigation"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="absolute inset-x-0 top-full max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-white/10 bg-brand-ink px-5 py-6 shadow-2xl xl:hidden"
        aria-label="Mobile navigation"
    >
        <div class="mx-auto flex max-w-7xl flex-col">
            @foreach ($links as $link)
                @php
                    $isActive = request()->routeIs($link['route']);
                @endphp

                <a
                    href="{{ route($link['route']) }}"
                    @click="open = false"
                    @class([
                        'border-b border-white/10 py-4 text-sm font-semibold uppercase tracking-[0.18em] transition',
                        'text-brand-gold' => $isActive,
                        'text-stone-200 hover:text-brand-gold' => ! $isActive,
                    ])
                    @if ($isActive) aria-current="page" @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
