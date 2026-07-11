<x-layouts.public
    :title="$page?->meta_title ?: 'Order Inquiry | Le Jardin Fine Dining'"
    :description="$page?->meta_description ?: 'Submit an Order Inquiry for pickup or delivery. Our team will confirm availability, final pricing, and arrangements directly with you.'"
>
    @php
        $fieldClass = 'mt-2 block min-h-12 w-full border border-stone-300 bg-brand-ivory px-4 py-3 text-base text-brand-ink placeholder:text-stone-400 transition hover:border-brand-gold-dark focus:border-brand-gold-dark focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/25';
        $labelClass = 'block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink';
        $errorClass = 'mt-2 text-sm text-brand-burgundy';
    @endphp

    <div data-home-motion data-order-inquiry-motion>
    <section data-public-hero data-gsap="hero" class="relative isolate flex min-h-[44rem] items-center overflow-hidden bg-brand-ink lg:min-h-screen">
        @if ($heroImage?->image_url)
            <div data-gsap="hero-image" class="absolute inset-0 -z-30 overflow-hidden">
                <x-public.responsive-image
                    :image="$heroImage"
                    :alt="$heroImage->alt_text ?: $heroImage->title ?: 'Elegant restaurant dining experience'"
                    variant="hero"
                    sizes="100vw"
                    width="1920"
                    height="1280"
                    loading="eager"
                    fetchpriority="high"
                    img-class="h-full w-full object-cover object-center"
                />
            </div>
        @else
            <div class="absolute inset-0 -z-30 bg-[radial-gradient(circle_at_70%_28%,rgba(201,164,93,0.25),transparent_34%),linear-gradient(145deg,#4d4437,#171916_68%)]"></div>
        @endif
        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.5),rgba(23,25,22,0.42)_35%,rgba(23,25,22,0.96))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/85 via-brand-ink/30 to-transparent"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-36 sm:px-6 lg:px-10 lg:pb-28 lg:pt-44">
            <div data-gsap="hero-content" class="max-w-4xl">
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold sm:text-sm">Dining beyond our tables</p>
                <h1 data-gsap-reveal class="mt-6 max-w-4xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-8xl">
                    {{ $page?->title ?: 'Bring Le Jardin to Your Table' }}
                </h1>
                <p data-gsap-reveal class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Share your preferred dishes and pickup or delivery details, and our team will personally review availability and pricing with you.' }}
                </p>
                <a data-gsap-reveal href="#order-inquiry-form" class="group mt-9 inline-flex items-center gap-4 text-xs font-semibold uppercase tracking-[0.22em] text-white transition hover:text-brand-gold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold">
                    Share Your Selection
                    <span class="transition duration-300 group-hover:translate-x-2 motion-reduce:transform-none" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <section id="order-inquiry-form" data-gsap="section" class="scroll-mt-20 overflow-hidden bg-brand-ivory py-20 text-brand-ink sm:py-24 lg:py-32">
        <div class="mx-auto grid max-w-7xl gap-14 px-5 sm:px-6 lg:grid-cols-[0.78fr_1.22fr] lg:gap-20 lg:px-10 xl:gap-28">
                <aside data-gsap-reveal class="lg:sticky lg:top-28 lg:self-start">
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-brand-gold-dark">Order Inquiry</p>
                    <h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl">A considered dining experience, beyond our doors</h2>
                    <p class="mt-6 max-w-xl text-base leading-8 text-brand-muted">
                        Share the dishes you are considering, your preferred timing, and any special notes. Our team will then confirm availability, pricing, and fulfillment arrangements with you.
                    </p>
                    <div class="mt-10 h-px w-16 bg-brand-gold" aria-hidden="true"></div>

                    <div data-gsap="process" class="mt-10 border-l border-brand-gold/45 pl-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-gold-dark">What to expect</p>
                        <ol class="mt-6 grid gap-6 text-sm leading-7 text-brand-muted">
                            <li data-gsap-reveal class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">01</span><span>Our team reviews your preferred dishes and timing.</span></li>
                            <li data-gsap-reveal class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">02</span><span>We contact you with availability, final pricing, and fulfillment details.</span></li>
                            <li data-gsap-reveal class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">03</span><span>Payment and pickup or delivery arrangements are then confirmed with you directly.</span></li>
                        </ol>
                    </div>

                    <div data-gsap-reveal class="mt-10 border border-brand-gold/35 bg-brand-paper p-6 text-sm leading-7 text-brand-muted">
                        <p class="font-semibold text-brand-ink">A note on confirmation</p>
                        <p class="mt-2">Submitting this form begins an Order Inquiry. Availability, final pricing, payment, and pickup or delivery arrangements are confirmed only after our team contacts you directly.</p>
                    </div>
                </aside>

                <div data-gsap="form-column">
                    @if (session()->has('status') || session()->has('success'))
                        <div data-gsap="feedback" class="mb-6 border border-emerald-700/25 bg-emerald-50 p-5 text-sm leading-7 text-emerald-900" role="status">
                            {{ session('status') ?? session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div data-gsap="feedback" class="mb-6 border border-brand-burgundy/25 bg-red-50 p-5 text-sm leading-7 text-brand-burgundy" role="alert">
                            <p class="font-semibold">Please review the highlighted fields and try again.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('order-inquiries.store') }}" class="border border-brand-gold/30 bg-white p-6 shadow-[0_28px_80px_rgba(23,25,22,0.1)] sm:p-9 lg:p-12" x-data="{ fulfillmentType: @js(old('fulfillment_type', 'pickup')) }" x-effect="$dispatch('order-inquiry:fulfillment-change', { fulfillmentType })" novalidate>
                        @csrf
                        <input type="hidden" name="source_page" value="order-inquiry">

                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off" value="{{ old('website') }}">
                        </div>

                        <div class="border-b border-brand-gold/25 pb-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your Details</p>
                            <h2 class="mt-3 font-display text-3xl leading-tight text-brand-ink sm:text-4xl">Share your Order Inquiry</h2>
                            <p class="mt-4 text-sm leading-7 text-brand-muted">Fields marked with <span class="text-brand-burgundy">*</span> are required.</p>
                        </div>

                        <div data-gsap-reveal class="mt-9 grid gap-x-6 gap-y-7 sm:grid-cols-2">
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

                        <fieldset data-gsap-reveal class="mt-10">
                            <legend class="{{ $labelClass }}">Pickup or delivery preference <span class="text-brand-burgundy">*</span></legend>
                            <div data-gsap="fulfillment-cards" class="mt-3 grid gap-3 sm:grid-cols-2">
                                <label data-gsap="card" class="flex cursor-pointer items-start gap-3 border border-stone-300 bg-brand-ivory p-5 text-sm text-brand-muted transition hover:border-brand-gold-dark has-[:checked]:border-brand-gold-dark has-[:checked]:bg-brand-gold/10 focus-within:ring-2 focus-within:ring-brand-gold/25">
                                    <input type="radio" name="fulfillment_type" value="pickup" x-model="fulfillmentType" @checked(old('fulfillment_type', 'pickup') === 'pickup') class="mt-1 border-brand-ink/30 text-brand-gold-dark focus:ring-brand-gold">
                                    <span><span class="block font-semibold text-brand-ink">Pickup</span><span class="mt-1 block leading-6">Our team will confirm pickup availability and timing.</span></span>
                                </label>
                                <label data-gsap="card" class="flex cursor-pointer items-start gap-3 border border-stone-300 bg-brand-ivory p-5 text-sm text-brand-muted transition hover:border-brand-gold-dark has-[:checked]:border-brand-gold-dark has-[:checked]:bg-brand-gold/10 focus-within:ring-2 focus-within:ring-brand-gold/25">
                                    <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillmentType" @checked(old('fulfillment_type') === 'delivery') class="mt-1 border-brand-ink/30 text-brand-gold-dark focus:ring-brand-gold">
                                    <span><span class="block font-semibold text-brand-ink">Delivery</span><span class="mt-1 block leading-6">Delivery details are reviewed and confirmed directly by staff.</span></span>
                                </label>
                            </div>
                            @error('fulfillment_type') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </fieldset>

                        <div data-gsap="delivery-address" class="mt-8 overflow-hidden" x-show="fulfillmentType === 'delivery'" x-bind:aria-hidden="fulfillmentType !== 'delivery'" x-bind:inert="fulfillmentType !== 'delivery'">
                            <label for="delivery_address" class="{{ $labelClass }}">Delivery address <span class="text-brand-burgundy">*</span></label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" x-bind:required="fulfillmentType === 'delivery'" x-bind:disabled="fulfillmentType !== 'delivery'" class="{{ $fieldClass }}" placeholder="Street, barangay, city, landmark, or delivery notes">{{ old('delivery_address') }}</textarea>
                            <p class="mt-2 text-xs leading-5 text-brand-muted">Delivery availability and any related details will be confirmed manually by the restaurant.</p>
                            @error('delivery_address') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>

                        <div data-gsap-reveal class="mt-12 border-b border-brand-gold/25 pb-7">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your Selection</p>
                            <h3 class="mt-3 font-display text-3xl text-brand-ink">Tell us what you have in mind</h3>
                        </div>

                        <div class="mt-8">
                            <label for="order_details" class="{{ $labelClass }}">Order details <span class="text-brand-burgundy">*</span></label>
                            <textarea id="order_details" name="order_details" rows="6" required class="{{ $fieldClass }}" placeholder="Example: 2x Truffle Pasta, 1x Grilled Salmon, 1x Chocolate Cake">{{ old('order_details') }}</textarea>
                            <p class="mt-2 text-xs leading-5 text-brand-muted">Include preferred menu items, quantities, and any item-specific notes. Submitting this form begins an inquiry and does not confirm an order.</p>
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

                        <div class="mt-8 border border-brand-gold/30 bg-brand-paper p-5 text-sm leading-7 text-brand-muted sm:p-6">
                            After submission, our team will review your inquiry, confirm availability, confirm the final total, and arrange payment or pickup/delivery details directly with you.
                        </div>

                        <div class="mt-9 border-t border-brand-gold/25 pt-8">
                            <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center bg-brand-ink px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:bg-brand-gold-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-gold sm:w-auto">
                                Submit Order Inquiry
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </section>
    </div>
</x-layouts.public>
