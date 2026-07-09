<x-layouts.public
    :title="$page?->meta_title ?: 'Gallery'"
    :description="$page?->meta_description ?: 'View our restaurant interiors, dishes, events, and ambiance.'"
>
    <x-public.hero
        eyebrow="Gallery"
        title="{{ $page?->title ?: 'Restaurant gallery' }}"
        description="{{ $page?->excerpt ?: 'Explore our interiors, dishes, events, and ambiance.' }}"
        primary-label="Explore Banquet Hall"
        :primary-url="route('banquet-hall')"
        secondary-label="Contact Us"
        :secondary-url="route('contact.create')"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleryImages as $image)
                <x-public.gallery-card :image="$image" />
            @empty
                <x-public.alert type="warning" class="md:col-span-2 lg:col-span-3">
                    No visible gallery images yet. Add images from the Filament admin panel.
                </x-public.alert>
            @endforelse
        </div>
    </section>
</x-layouts.public>
