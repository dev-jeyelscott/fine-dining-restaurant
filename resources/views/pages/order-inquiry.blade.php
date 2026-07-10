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
            action="{{ route('order-inquiries.store') }}"
            class="mt-8 space-y-8 rounded-2xl border border-white/10 bg-white/[0.03] p-6 shadow-2xl shadow-black/20"
            x-data="{ fulfillmentType: @js(old('fulfillment_type', 'pickup')) }"
            novalidate
        >
            @csrf

            <input type="hidden" name="source_page" value="order-inquiry">

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
                    Tell us about your order inquiry
                </h2>
                <p class="mt-2 text-sm leading-6 text-stone-400">
                    Share the items you are interested in, your preferred pickup or delivery time, and any notes. This does not place a paid order or reserve delivery availability.
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
                    <label for="preferred_time" class="block text-sm font-medium text-stone-200">
                        Preferred pickup or delivery time <span class="text-red-300">*</span>
                    </label>
                    <input
                        id="preferred_time"
                        name="preferred_time"
                        type="text"
                        value="{{ old('preferred_time') }}"
                        required
                        class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                        placeholder="Today at 6:30 PM"
                    >
                    @error('preferred_time')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <fieldset>
                <legend class="block text-sm font-medium text-stone-200">
                    Pickup or delivery preference <span class="text-red-300">*</span>
                </legend>

                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-white/10 bg-stone-950/50 p-4 text-sm text-stone-200 has-[:checked]:border-amber-300/70 has-[:checked]:bg-amber-300/10">
                        <input
                            type="radio"
                            name="fulfillment_type"
                            value="pickup"
                            x-model="fulfillmentType"
                            @checked(old('fulfillment_type', 'pickup') === 'pickup')
                            class="mt-1 border-white/20 bg-stone-950 text-amber-300 focus:ring-amber-300"
                        >
                        <span>
                            <span class="block font-semibold text-white">Pickup</span>
                            <span class="mt-1 block text-stone-400">Our team will confirm pickup availability and timing.</span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-white/10 bg-stone-950/50 p-4 text-sm text-stone-200 has-[:checked]:border-amber-300/70 has-[:checked]:bg-amber-300/10">
                        <input
                            type="radio"
                            name="fulfillment_type"
                            value="delivery"
                            x-model="fulfillmentType"
                            @checked(old('fulfillment_type') === 'delivery')
                            class="mt-1 border-white/20 bg-stone-950 text-amber-300 focus:ring-amber-300"
                        >
                        <span>
                            <span class="block font-semibold text-white">Delivery</span>
                            <span class="mt-1 block text-stone-400">Delivery details are reviewed and confirmed directly by staff.</span>
                        </span>
                    </label>
                </div>

                @error('fulfillment_type')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </fieldset>

            <div x-show="fulfillmentType === 'delivery'" x-cloak>
                <label for="delivery_address" class="block text-sm font-medium text-stone-200">
                    Delivery address <span class="text-red-300">*</span>
                </label>
                <textarea
                    id="delivery_address"
                    name="delivery_address"
                    rows="3"
                    class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    placeholder="Street, barangay, city, landmark, or delivery notes"
                >{{ old('delivery_address') }}</textarea>
                <p class="mt-2 text-xs leading-5 text-stone-500">
                    Delivery availability and any related details will be confirmed manually by the restaurant.
                </p>
                @error('delivery_address')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="order_details" class="block text-sm font-medium text-stone-200">
                    Order details <span class="text-red-300">*</span>
                </label>
                <textarea
                    id="order_details"
                    name="order_details"
                    rows="6"
                    required
                    class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    placeholder="Example: 2x Truffle Pasta, 1x Grilled Salmon, 1x Chocolate Cake"
                >{{ old('order_details') }}</textarea>
                <p class="mt-2 text-xs leading-5 text-stone-500">
                    You may type menu items, quantities, and any item-specific notes here. This is an inquiry only, not a shopping cart.
                </p>
                @error('order_details')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-stone-200">
                    Estimated quantity <span class="text-red-300">*</span>
                </label>
                <input
                    id="quantity"
                    name="quantity"
                    type="number"
                    min="1"
                    step="1"
                    value="{{ old('quantity', 1) }}"
                    required
                    class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                >
                <p class="mt-2 text-xs leading-5 text-stone-500">
                    Use this as the total estimated quantity if your order details include multiple items.
                </p>
                @error('quantity')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="special_instructions" class="block text-sm font-medium text-stone-200">
                    Special instructions
                </label>
                <textarea
                    id="special_instructions"
                    name="special_instructions"
                    rows="4"
                    class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                    placeholder="Allergies, dietary notes, packaging requests, or other instructions"
                >{{ old('special_instructions') }}</textarea>
                @error('special_instructions')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-2xl border border-amber-300/20 bg-amber-300/10 p-4 text-sm leading-6 text-amber-100">
                After submission, our team will review your inquiry, confirm availability, confirm the final total, and arrange payment or pickup/delivery details directly with you.
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-5 text-stone-500">
                    Fields marked with <span class="text-red-300">*</span> are required.
                </p>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-stone-950"
                >
                    Submit Order Inquiry
                </button>
            </div>
        </form>
    </section>
</x-layouts.public>
