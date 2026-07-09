<x-layouts.public
    :title="$page?->meta_title ?: 'Banquet Hall'"
    :description="$page?->meta_description ?: 'Learn about our banquet hall, event space, capacity, and inquiry options.'"
>
    <x-public.hero
        eyebrow="Banquet Hall"
        title="{{ $page?->title ?: 'Celebrate meaningful gatherings' }}"
        description="{{ $page?->excerpt ?: 'Explore our event space for birthdays, family celebrations, corporate meals, and special occasions.' }}"
        primary-label="Contact Us"
        :primary-url="route('contact.create')"
        secondary-label="Request a Reservation"
        :secondary-url="route('reservation-request.create')"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2">
            <div>
                <h2 class="text-3xl font-bold text-white">Banquet and event inquiries</h2>

                <div class="mt-5 space-y-4 text-stone-400">
                    @if ($page?->content)
                        <div class="prose prose-invert max-w-none">
                            {!! $page->content !!}
                        </div>
                    @else
                        <p>
                            Our banquet hall is suited for family celebrations, private dining,
                            corporate meals, and other special gatherings.
                        </p>
                        <p>
                            Please contact our team for capacity, availability, menu options,
                            and event details. All banquet inquiries are manually reviewed.
                        </p>
                    @endif
                </div>

                <div class="mt-8 rounded-2xl border border-amber-300/20 bg-amber-300/10 p-5 text-sm leading-6 text-amber-100">
                    Banquet requests are inquiries only. Our team will review your preferred date,
                    guest count, event type, and details before confirming availability.
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @forelse ($banquetImages as $image)
                    <x-public.gallery-card :image="$image" />
                @empty
                    <x-public.alert type="warning" class="sm:col-span-2">
                        No banquet images yet. Add gallery images with category <strong>banquet</strong>.
                    </x-public.alert>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.public>
