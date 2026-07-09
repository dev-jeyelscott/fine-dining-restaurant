<x-layouts.public
    :title="$page?->meta_title ?: 'Reservation Request'"
    :description="$page?->meta_description ?: 'Submit a reservation request for manual restaurant review and confirmation.'"
>
    <x-public.hero
        eyebrow="Reservation Request"
        title="{{ $page?->title ?: 'Request a table' }}"
        description="{{ $page?->excerpt ?: 'Send your preferred date, time, guest count, and notes. Our team will review and contact you to confirm availability.' }}"
        primary-label="Contact Us"
        :primary-url="route('contact.create')"
        secondary-label="View Our Menu"
        :secondary-url="route('menu')"
    />

    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <x-public.alert type="info">
            This is a Reservation Request, not a confirmed reservation. Our team will manually review your request and contact you to confirm availability.
        </x-public.alert>

        <div class="mt-8 rounded-2xl border border-white/10 bg-white/[0.03] p-6">
            <p class="text-sm leading-6 text-stone-400">
                The reservation form UI is ready for the next slice. Next, we will add server-side validation,
                database storage, email notification, admin visibility, and success/error messaging.
            </p>
        </div>
    </section>
</x-layouts.public>
