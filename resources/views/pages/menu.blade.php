<x-layouts.public
    :title="$page?->meta_title ?: 'Menu'"
    :description="$page?->meta_description ?: 'Browse our menu categories, dishes, descriptions, and prices.'"
>
    @php
        $menuItems = $categories->flatMap->menuItems;
        $heroItem = $menuItems->first(fn ($item) => filled($item->image_url));
    @endphp

    <section class="relative isolate flex min-h-[38rem] items-end overflow-hidden bg-brand-ink lg:min-h-[44rem]">
        @if ($heroItem?->image_url)
            <x-public.responsive-image
                :image="$heroItem"
                alt=""
                variant="hero"
                sizes="100vw"
                width="1920"
                height="1280"
                loading="eager"
                fetchpriority="high"
                img-class="absolute inset-0 -z-30 h-full w-full object-cover"
            />
        @else
            <div class="absolute inset-0 -z-30 bg-[radial-gradient(circle_at_72%_28%,rgba(201,164,93,0.28),transparent_25%),linear-gradient(135deg,#2f302a,#171916_62%)]"></div>
        @endif

        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.42),rgba(23,25,22,0.62)_45%,rgba(23,25,22,0.98))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/90 via-brand-ink/55 to-brand-ink/20"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-32 sm:px-6 sm:pb-24 lg:px-10 lg:pb-28">
            <div class="max-w-4xl">
                <p class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold">Our Menu</p>
                <h1 class="mt-6 max-w-3xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-7xl">
                    {{ $page?->title ?: 'A thoughtful expression of the season' }}
                </h1>
                <p class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Explore carefully prepared dishes, considered pairings, and familiar flavours elevated with finesse.' }}
                </p>

                <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                    <a
                        href="#menu-selections"
                        class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition duration-300 hover:bg-brand-gold-dark hover:text-white"
                    >
                        Explore the Menu
                    </a>
                    <a
                        href="{{ route('reservation-request.create') }}"
                        class="inline-flex min-h-12 items-center justify-center border border-white/60 px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:border-brand-gold hover:bg-brand-gold hover:text-brand-ink"
                    >
                        Request a Reservation
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <nav class="border-y border-brand-gold/20 bg-brand-ink-soft" aria-label="Menu categories">
            <div class="mx-auto flex max-w-7xl gap-7 overflow-x-auto px-5 py-5 sm:px-6 lg:justify-center lg:px-10">
                @foreach ($categories as $category)
                    <a
                        href="#category-{{ $category->slug }}"
                        class="shrink-0 text-[0.68rem] font-semibold uppercase tracking-[0.22em] text-stone-300 transition hover:text-brand-gold"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    <section id="menu-selections" class="bg-brand-ink py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
            <x-public.section-heading
                eyebrow="From Our Kitchen"
                title="The full menu"
                description="Each selection is prepared with care and served with the warm, attentive hospitality at the heart of our dining room."
            />

            <div class="mt-20 divide-y divide-brand-gold/20">
                @forelse ($categories as $category)
                    <section id="category-{{ $category->slug }}" class="scroll-mt-28 py-16 first:pt-0 last:pb-0 lg:py-24">
                        <div class="grid gap-12 lg:grid-cols-[0.32fr_0.68fr] lg:gap-16">
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-[0.3em] text-brand-gold">
                                    Course {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </p>
                                <h2 class="mt-4 font-display text-4xl leading-tight text-brand-ivory sm:text-5xl">
                                    {{ $category->name }}
                                </h2>

                                @if ($category->description)
                                    <p class="mt-5 max-w-md text-sm leading-7 text-stone-400">
                                        {{ $category->description }}
                                    </p>
                                @endif

                                <div class="mt-8 h-px w-16 bg-brand-gold/70" aria-hidden="true"></div>
                            </div>

                            <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2">
                                @forelse ($category->menuItems as $item)
                                    <x-public.menu-card :item="$item" variant="luxury" />
                                @empty
                                    <x-public.alert type="warning" class="sm:col-span-2">
                                        No visible menu items in this category yet.
                                    </x-public.alert>
                                @endforelse
                            </div>
                        </div>
                    </section>
                @empty
                    <x-public.alert type="warning">
                        No visible menu categories yet. Add menu categories and menu items from the Filament admin panel.
                    </x-public.alert>
                @endforelse
            </div>
        </div>
    </section>

    <section class="relative isolate overflow-hidden bg-brand-paper py-24 text-brand-ink lg:py-28">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_85%_20%,rgba(201,164,93,0.18),transparent_28%)]"></div>
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 sm:px-6 lg:grid-cols-[1fr_auto] lg:px-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold-dark">Dining With Us</p>
                <h2 class="mt-5 max-w-3xl font-display text-4xl leading-tight sm:text-5xl">
                    Let us prepare something memorable for your table.
                </h2>
                <p class="mt-6 max-w-2xl text-base leading-8 text-brand-muted">
                    Planning to dine with us or enjoy a selection at home? Send your request and our team will personally review the details and confirm availability.
                </p>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row lg:flex-col">
                <a
                    href="{{ route('reservation-request.create') }}"
                    class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-center text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white"
                >
                    Request a Reservation
                </a>
                <a
                    href="{{ route('order-inquiry.create') }}"
                    class="inline-flex min-h-12 items-center justify-center border border-brand-ink px-7 py-3 text-center text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-ink hover:text-brand-ivory"
                >
                    Submit Order Inquiry
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
