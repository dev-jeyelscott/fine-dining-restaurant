<x-layouts.public
    :title="$page?->meta_title ?: 'Order Inquiry'"
    :description="$page?->meta_description ?: 'Submit an order inquiry for manual restaurant review.'"
>
    <x-public.hero
        eyebrow="Order Inquiry"
        title="{{ $page?->title ?: 'Submit an order inquiry' }}"
        description="{{ $page?->excerpt ?: 'Send your pickup or delivery preference and order details. Our team will review availability, final total, and next steps directly with you.' }}"
        primary-label="View Our Menu"
        :primary-url="route('menu')"
        secondary-label="Contact Us"
        :secondary-url="route('contact.create')"
    />

    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <x-public.alert type="info">
            This is an Order Inquiry, not checkout or online payment. Our team will manually confirm availability, final total, and pickup or delivery details directly.
        </x-public.alert>

        <div class="mt-8 rounded-2xl border border-white/10 bg-white/[0.03] p-6">
            <p class="text-sm leading-6 text-stone-400">
                The order inquiry form UI is ready for the next slice. Next, we will add server-side validation,
                database storage, email notification, admin visibility, and success/error messaging.
            </p>
        </div>
    </section>
</x-layouts.public>
