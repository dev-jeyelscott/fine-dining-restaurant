<x-layouts.public
    :title="$page?->meta_title ?: 'Home'"
    :description="$page?->meta_description ?: 'Fine dining restaurant for families, guests, events, reservation requests, and order inquiries.'"
>
    <x-public.hero
        eyebrow="Fine Dining Restaurant"
        title="{{ $page?->title ?: 'A warm and memorable dining experience' }}"
        description="{{ $page?->excerpt ?: 'Enjoy thoughtfully prepared dishes, elegant ambiance, family-friendly service, and spaces for special gatherings.' }}"
        primary-label="View Our Menu"
        :primary-url="route('menu')"
        secondary-label="Request a Reservation"
        :secondary-url="route('reservation-request.create')"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <x-public.section-heading
            eyebrow="Welcome"
            title="Fine dining made welcoming"
            description="Discover our menu, explore our ambiance, submit a Reservation Request, or send an Order Inquiry for manual restaurant review."
        />

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <a href="{{ route('menu') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 transition hover:border-amber-300/40">
                <h3 class="text-xl font-semibold text-white">Curated Menu</h3>
                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Browse dishes, categories, descriptions, and prices.
                </p>
            </a>

            <a href="{{ route('banquet-hall') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 transition hover:border-amber-300/40">
                <h3 class="text-xl font-semibold text-white">Banquet Hall</h3>
                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Explore event space details for family gatherings and celebrations.
                </p>
            </a>

            <a href="{{ route('contact.create') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 transition hover:border-amber-300/40">
                <h3 class="text-xl font-semibold text-white">Contact Us</h3>
                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Find our phone, email, location, map, and inquiry form.
                </p>
            </a>
        </div>
    </section>

    <section class="bg-stone-900/60">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Menu Preview"
                title="Featured dishes"
                description="A preview of admin-managed menu items."
            />

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredMenuItems as $item)
                    <x-public.menu-card :item="$item" />
                @empty
                    <x-public.alert type="warning" class="md:col-span-2 lg:col-span-3">
                        No visible menu items yet. Add menu items from the Filament admin panel.
                    </x-public.alert>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('menu') }}" class="inline-flex rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 hover:bg-amber-200">
                    View Our Menu
                </a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <x-public.section-heading
            eyebrow="Gallery"
            title="Ambiance, dishes, and events"
            description="A glimpse of the restaurant experience."
        />

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleryImages as $image)
                <x-public.gallery-card :image="$image" />
            @empty
                <x-public.alert type="warning" class="md:col-span-2 lg:col-span-3">
                    No visible gallery images yet. Add images from the Filament admin panel.
                </x-public.alert>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('gallery') }}" class="inline-flex rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-stone-100 hover:border-amber-200 hover:text-amber-200">
                View Gallery
            </a>
        </div>
    </section>
</x-layouts.public>
