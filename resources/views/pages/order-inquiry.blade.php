<x-layouts.public
    :title="$page?->meta_title ?: 'Order Inquiry'"
    :description="$page?->meta_description ?: 'Submit an order inquiry for manual restaurant review.'"
>
    @php
        $fieldClass = 'mt-2 block min-h-13 w-full border border-brand-ink/20 bg-white px-4 py-3 text-base text-brand-ink shadow-sm transition placeholder:text-brand-muted/70 hover:border-brand-gold-dark/60 focus:border-brand-gold-dark focus:outline-none focus:ring-2 focus:ring-brand-gold/25';
        $labelClass = 'block text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink';
        $errorClass = 'mt-2 text-sm text-brand-burgundy';
    @endphp

    <section class="relative isolate flex min-h-[38rem] items-end overflow-hidden bg-brand-ink lg:min-h-[44rem]">
        <x-public.responsive-image
            :image="$heroImage"
            :alt="$heroImage?->alt_text ?: $heroImage?->title ?: 'Elegant restaurant dining room'"
            variant="hero"
            sizes="100vw"
            width="1920"
            height="1280"
            loading="eager"
            fetchpriority="high"
            img-class="absolute inset-0 -z-30 h-full w-full object-cover object-center"
        />
        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.62),rgba(23,25,22,0.48)_35%,rgba(23,25,22,0.96))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/85 via-brand-ink/35 to-brand-ink/20"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-40 sm:px-6 sm:pb-24 lg:px-10 lg:pb-28">
            <div class="max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold">Dining beyond our tables</p>
                <h1 class="mt-6 font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-7xl">
                    {{ $page?->title ?: 'Submit an order inquiry' }}
                </h1>
                <p class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Send your pickup or delivery preference and order details. Our team will review availability, final total, and next steps directly with you.' }}
                </p>
                <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                    <a href="#order-inquiry-form" class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white">
                        Begin Your Inquiry
                    </a>
                    <a href="{{ route('menu') }}" class="inline-flex min-h-12 items-center justify-center border border-white/60 px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:border-brand-gold hover:bg-brand-gold hover:text-brand-ink">
                        View Our Menu
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="order-inquiry-form" class="scroll-mt-20 bg-brand-paper py-20 text-brand-ink sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
            <div class="grid items-start gap-12 lg:grid-cols-[0.72fr_1.28fr] lg:gap-16 xl:gap-24">
                <aside class="lg:sticky lg:top-28">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold-dark">Order Inquiry</p>
                    <h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl">Bring the experience home</h2>
                    <p class="mt-6 text-base leading-8 text-brand-muted">
                        Share the items you are interested in, your preferred pickup or delivery time, and any notes. This does not place a paid order or reserve delivery availability.
                    </p>
                    <div class="mt-9 h-px w-20 bg-brand-gold" aria-hidden="true"></div>

                    <div class="mt-9 bg-brand-ink p-7 text-brand-ivory sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-brand-gold">What happens next</p>
                        <ol class="mt-6 grid gap-6 text-sm leading-6 text-stone-300">
                            <li class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold">01</span><span>Our team reviews your requested items and timing.</span></li>
                            <li class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold">02</span><span>We confirm availability, final total, and fulfillment details.</span></li>
                            <li class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold">03</span><span>Payment and pickup or delivery arrangements are made directly with you.</span></li>
                        </ol>
                        <a href="{{ route('contact.create') }}" class="group mt-8 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold transition hover:text-white">
                            Contact Us <span class="transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </aside>

                <div>
                    <div class="border-l-2 border-brand-gold bg-brand-ivory px-5 py-4 text-sm leading-6 text-brand-muted sm:px-6">
                        <strong class="font-semibold text-brand-ink">A personal review, every time.</strong>
                        This is an Order Inquiry, not checkout or online payment. Our team will manually confirm availability, final total, and pickup or delivery details directly.
                    </div>

                    @if (session()->has('status') || session()->has('success'))
                        <div class="mt-6 border border-emerald-700/25 bg-emerald-50 px-5 py-4 text-sm leading-6 text-emerald-900" role="status">
                            {{ session('status') ?? session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 border border-brand-burgundy/25 bg-red-50 px-5 py-4 text-sm leading-6 text-brand-burgundy" role="alert">
                            <p class="font-semibold">Please review the highlighted fields and try again.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('order-inquiries.store') }}" class="mt-8 bg-brand-ivory p-6 shadow-[0_24px_70px_rgba(23,25,22,0.12)] sm:p-9 lg:p-12" x-data="{ fulfillmentType: @js(old('fulfillment_type', 'pickup')) }" novalidate>
                        @csrf
                        <input type="hidden" name="source_page" value="order-inquiry">

                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off" value="{{ old('website') }}">
                        </div>

                        <div class="border-b border-brand-gold/40 pb-7">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your Details</p>
                            <h3 class="mt-3 font-display text-3xl text-brand-ink">How may we reach you?</h3>
                        </div>

                        <div class="mt-8 grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="customer_name" class="{{ $labelClass }}">Full name <span class="text-brand-burgundy">*</span></label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required class="{{ $fieldClass }}" placeholder="Juan dela Cruz">
                                @error('customer_name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="{{ $labelClass }}">Phone number <span class="text-brand-burgundy">*</span></label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required class="{{ $fieldClass }}" placeholder="+63 912 345 6789">
                                @error('phone') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="{{ $labelClass }}">Email address <span class="text-brand-burgundy">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="{{ $fieldClass }}" placeholder="you@example.com">
                                @error('email') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="preferred_time" class="{{ $labelClass }}">Preferred pickup or delivery time <span class="text-brand-burgundy">*</span></label>
                                <input id="preferred_time" name="preferred_time" type="text" value="{{ old('preferred_time') }}" required class="{{ $fieldClass }}" placeholder="Today at 6:30 PM">
                                @error('preferred_time') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <fieldset class="mt-10">
                            <legend class="{{ $labelClass }}">Pickup or delivery preference <span class="text-brand-burgundy">*</span></legend>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <label class="flex cursor-pointer items-start gap-3 border border-brand-ink/20 bg-white p-5 text-sm text-brand-muted transition hover:border-brand-gold-dark/60 has-[:checked]:border-brand-gold-dark has-[:checked]:bg-brand-gold/10">
                                    <input type="radio" name="fulfillment_type" value="pickup" x-model="fulfillmentType" @checked(old('fulfillment_type', 'pickup') === 'pickup') class="mt-1 border-brand-ink/30 text-brand-gold-dark focus:ring-brand-gold">
                                    <span><span class="block font-semibold text-brand-ink">Pickup</span><span class="mt-1 block leading-6">Our team will confirm pickup availability and timing.</span></span>
                                </label>
                                <label class="flex cursor-pointer items-start gap-3 border border-brand-ink/20 bg-white p-5 text-sm text-brand-muted transition hover:border-brand-gold-dark/60 has-[:checked]:border-brand-gold-dark has-[:checked]:bg-brand-gold/10">
                                    <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillmentType" @checked(old('fulfillment_type') === 'delivery') class="mt-1 border-brand-ink/30 text-brand-gold-dark focus:ring-brand-gold">
                                    <span><span class="block font-semibold text-brand-ink">Delivery</span><span class="mt-1 block leading-6">Delivery details are reviewed and confirmed directly by staff.</span></span>
                                </label>
                            </div>
                            @error('fulfillment_type') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </fieldset>

                        <div class="mt-8" x-show="fulfillmentType === 'delivery'" x-cloak>
                            <label for="delivery_address" class="{{ $labelClass }}">Delivery address <span class="text-brand-burgundy">*</span></label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" class="{{ $fieldClass }}" placeholder="Street, barangay, city, landmark, or delivery notes">{{ old('delivery_address') }}</textarea>
                            <p class="mt-2 text-xs leading-5 text-brand-muted">Delivery availability and any related details will be confirmed manually by the restaurant.</p>
                            @error('delivery_address') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-12 border-b border-brand-gold/40 pb-7">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your Selection</p>
                            <h3 class="mt-3 font-display text-3xl text-brand-ink">Tell us what you have in mind</h3>
                        </div>

                        <div class="mt-8">
                            <label for="order_details" class="{{ $labelClass }}">Order details <span class="text-brand-burgundy">*</span></label>
                            <textarea id="order_details" name="order_details" rows="6" required class="{{ $fieldClass }}" placeholder="Example: 2x Truffle Pasta, 1x Grilled Salmon, 1x Chocolate Cake">{{ old('order_details') }}</textarea>
                            <p class="mt-2 text-xs leading-5 text-brand-muted">You may type menu items, quantities, and any item-specific notes here. This is an inquiry only, not a shopping cart.</p>
                            @error('order_details') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-6">
                            <label for="quantity" class="{{ $labelClass }}">Estimated quantity <span class="text-brand-burgundy">*</span></label>
                            <input id="quantity" name="quantity" type="number" min="1" step="1" value="{{ old('quantity', 1) }}" required class="{{ $fieldClass }}">
                            <p class="mt-2 text-xs leading-5 text-brand-muted">Use this as the total estimated quantity if your order details include multiple items.</p>
                            @error('quantity') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-6">
                            <label for="special_instructions" class="{{ $labelClass }}">Special instructions</label>
                            <textarea id="special_instructions" name="special_instructions" rows="4" class="{{ $fieldClass }}" placeholder="Allergies, dietary notes, packaging requests, or other instructions">{{ old('special_instructions') }}</textarea>
                            @error('special_instructions') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-8 border-l-2 border-brand-gold bg-brand-paper px-5 py-4 text-sm leading-6 text-brand-muted">
                            After submission, our team will review your inquiry, confirm availability, confirm the final total, and arrange payment or pickup/delivery details directly with you.
                        </div>

                        <div class="mt-8 flex flex-col gap-5 border-t border-brand-ink/10 pt-8 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs leading-5 text-brand-muted">Fields marked with <span class="text-brand-burgundy">*</span> are required.</p>
                            <button type="submit" class="inline-flex min-h-13 items-center justify-center bg-brand-gold px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-gold focus:ring-offset-2 focus:ring-offset-brand-ivory">
                                Submit Order Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
