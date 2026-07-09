<x-layouts.public
    :title="$page?->meta_title ?: 'Menu'"
    :description="$page?->meta_description ?: 'Browse our menu categories, dishes, descriptions, and prices.'"
>
    <x-public.hero
        eyebrow="Our Menu"
        title="{{ $page?->title ?: 'Explore our menu' }}"
        description="{{ $page?->excerpt ?: 'Browse our categories, dishes, descriptions, and prices.' }}"
        primary-label="Submit Order Inquiry"
        :primary-url="route('order-inquiry.create')"
        secondary-label="Request a Reservation"
        :secondary-url="route('reservation-request.create')"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @forelse ($categories as $category)
            <div class="mb-14">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>

                    @if ($category->description)
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-stone-400">
                            {{ $category->description }}
                        </p>
                    @endif
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($category->menuItems as $item)
                        <x-public.menu-card :item="$item" />
                    @empty
                        <x-public.alert type="warning">
                            No visible menu items in this category yet.
                        </x-public.alert>
                    @endforelse
                </div>
            </div>
        @empty
            <x-public.alert type="warning">
                No visible menu categories yet. Add menu categories and menu items from the Filament admin panel.
            </x-public.alert>
        @endforelse
    </section>
</x-layouts.public>
