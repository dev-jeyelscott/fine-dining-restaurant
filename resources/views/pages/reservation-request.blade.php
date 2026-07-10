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

        @if (session()->has('status') || session()->has('success'))
            <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 p-4 text-sm leading-6 text-emerald-100">
                {{ session('status') ?? session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-400/30 bg-red-400/10 p-4 text-sm leading-6 text-red-100">
                <p class="font-semibold">Please review the highlighted fields and try again.</p>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('reservation-requests.store') }}"
            class="mt-8 space-y-8 rounded-2xl border border-white/10 bg-white/[0.03] p-6 shadow-2xl shadow-black/20"
            novalidate
        >
            @csrf

            <input type="hidden" name="source_page" value="reservation-request">

            {{-- Honeypot: must remain empty. --}}
            <div class="hidden" aria-hidden="true">
                <label for="website">Website</label>
                <input
                    id="website"
                    name="website"
                    type="text"
                    tabindex="-1"
                    autocomplete="off"
                    value="{{ old('website') }}"
                >
            </div>

            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-white">
                    Tell us about your preferred reservation
                </h2>
                <p class="mt-2 text-sm leading-6 text-stone-400">
                    Share your preferred date, time, party size, and any special requests. This request will be reviewed by our team before your reservation is confirmed.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-stone-200">
                        Full name <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="customer_name"
                        name="customer_name"
                        type="text"
                        value="{{ old('customer_name') }}"
                        autocomplete="name"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                        placeholder="Juan dela Cruz"
                    >
                    @error('customer_name')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-stone-200">
                        Phone number <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                        placeholder="+63 912 345 6789"
                    >
                    @error('phone')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-stone-200">
                        Email address <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="guest_count" class="block text-sm font-medium text-stone-200">
                        Number of guests <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="guest_count"
                        name="guest_count"
                        type="number"
                        min="1"
                        max="200"
                        step="1"
                        value="{{ old('guest_count', 2) }}"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    >
                    @error('guest_count')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preferred_date" class="block text-sm font-medium text-stone-200">
                        Preferred reservation date <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="preferred_date"
                        name="preferred_date"
                        type="date"
                        value="{{ old('preferred_date') }}"
                        min="{{ now()->toDateString() }}"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    >
                    <p class="mt-2 text-xs leading-5 text-stone-500">
                        Date selection is a preference only and is subject to manual availability review.
                    </p>
                    @error('preferred_date')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preferred_time" class="block text-sm font-medium text-stone-200">
                        Preferred time <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="preferred_time"
                        name="preferred_time"
                        type="time"
                        value="{{ old('preferred_time') }}"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    >
                    @error('preferred_time')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-stone-950/40 p-4">
                <input type="hidden" name="is_banquet_or_event" value="0">

                <label class="flex cursor-pointer items-start gap-3 text-sm text-stone-200">
                    <input
                        type="checkbox"
                        name="is_banquet_or_event"
                        value="1"
                        @checked(old('is_banquet_or_event'))
                        class="mt-1 rounded border-white/20 bg-stone-950 text-amber-300 focus:ring-amber-300"
                    >
                    <span>
                        <span class="block font-semibold text-white">
                            This is for a banquet or event inquiry
                        </span>
                        <span class="mt-1 block leading-6 text-stone-400">
                            Select this if your request is for a larger gathering, private event, or banquet hall inquiry. Our team will follow up directly with next steps.
                        </span>
                    </span>
                </label>

                @error('is_banquet_or_event')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="special_requests" class="block text-sm font-medium text-stone-200">
                    Special requests or notes
                </label>
                <textarea
                    id="special_requests"
                    name="special_requests"
                    rows="5"
                    class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    placeholder="Dietary needs, accessibility requests, seating preference, celebration details, or other notes"
                >{{ old('special_requests') }}</textarea>
                <p class="mt-2 text-xs leading-5 text-stone-500">
                    We will do our best to accommodate requests, but details are not guaranteed until confirmed by the restaurant.
                </p>
                @error('special_requests')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-2xl border border-amber-300/20 bg-amber-300/10 p-4 text-sm leading-6 text-amber-100">
                After submission, our team will review your request and contact you to confirm availability. This is not yet a confirmed reservation.
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-5 text-stone-500">
                    Fields marked with <span class="text-red-300">*</span> are required.
                </p>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-stone-950"
                >
                    Request a Reservation
                </button>
            </div>
        </form>
    </section>
</x-layouts.public>
