<x-layouts.public
    :title="$page?->meta_title ?: 'Contact'"
    :description="$page?->meta_description ?: 'Contact us for reservations, order inquiries, banquet inquiries, location, and general questions.'"
>
    <x-public.hero
        eyebrow="Contact Us"
        title="{{ $page?->title ?: 'Get in touch' }}"
        description="{{ $page?->excerpt ?: 'Find our phone, email, address, map, and inquiry options.' }}"
        primary-label="Request a Reservation"
        :primary-url="route('reservation-request.create')"
        secondary-label="Submit Order Inquiry"
        :secondary-url="route('order-inquiry.create')"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6">
                <h2 class="text-2xl font-bold text-white">Restaurant details</h2>

                <dl class="mt-6 space-y-5 text-sm">
                    @if ($settings['phone'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Phone</dt>
                            <dd class="mt-1 text-stone-400">{{ $settings['phone'] }}</dd>
                        </div>
                    @endif

                    @if ($settings['email'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Email</dt>
                            <dd class="mt-1 text-stone-400">{{ $settings['email'] }}</dd>
                        </div>
                    @endif

                    @if ($settings['address'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Address</dt>
                            <dd class="mt-1 text-stone-400">{{ $settings['address'] }}</dd>
                        </div>
                    @endif

                    @if ($settings['map_link'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Map</dt>
                            <dd class="mt-1">
                                <a
                                    href="{{ $settings['map_link'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-amber-300 hover:text-amber-200"
                                >
                                    Open location map
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6">
                <h2 class="text-2xl font-bold text-white">Send an inquiry</h2>

                <p class="mt-4 text-sm leading-6 text-stone-400">
                    The contact form workflow will be implemented in the next slice with server-side validation,
                    database storage, email notification, and admin visibility.
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('reservation-request.create') }}" class="rounded-full bg-amber-300 px-5 py-3 text-center text-sm font-semibold text-stone-950 hover:bg-amber-200">
                        Request a Reservation
                    </a>

                    <a href="{{ route('order-inquiry.create') }}" class="rounded-full border border-white/15 px-5 py-3 text-center text-sm font-semibold text-stone-100 hover:border-amber-200 hover:text-amber-200">
                        Submit Order Inquiry
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
