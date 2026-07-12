<x-layouts.public
    :title="$page?->meta_title ?: 'Reservation Request'"
    :description="$page?->meta_description ?: 'Submit a reservation request for manual restaurant review and confirmation.'"
>
    <div data-home-motion data-reservation-motion>
    <section data-reservation-hero data-public-hero class="public-hero-viewport relative isolate flex items-center overflow-hidden bg-brand-ink">
        <div data-gsap="hero-image" class="absolute inset-0 -z-30">
        @if ($heroImage?->image_url)
                <x-public.responsive-image
                    :image="$heroImage"
                    :alt="$heroImage->alt_text ?: $heroImage->title ?: 'Elegant restaurant dining room'"
                    variant="hero"
                    sizes="100vw"
                    width="1920"
                    height="1280"
                    loading="eager"
                    fetchpriority="high"
                    img-class="absolute inset-0 h-full w-full object-cover object-center"
                />
        @else
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_25%,rgba(201,164,93,0.3),transparent_26%),linear-gradient(135deg,#353126,#171916_68%)]"></div>
        @endif
        </div>

        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.5),rgba(23,25,22,0.42)_35%,rgba(23,25,22,0.96))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/85 via-brand-ink/30 to-transparent"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-12 pt-28 sm:px-6 sm:pb-20 sm:pt-36 lg:px-10 lg:pb-28 lg:pt-44">
            <div data-gsap="hero-content" class="max-w-4xl">
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold sm:text-sm">An evening worth anticipating</p>
                <h1 data-gsap-reveal class="mt-6 max-w-4xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-8xl">
                    {{ $page?->title ?: 'Reserve your place at the table' }}
                </h1>
                <p data-gsap-reveal class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Share the details of your preferred visit. Our team will personally review your request and be in touch regarding availability.' }}
                </p>
                <a data-gsap-reveal href="#reservation-form" class="group mt-9 inline-flex items-center gap-4 text-xs font-semibold uppercase tracking-[0.22em] text-white transition hover:text-brand-gold">
                    Begin your Reservation Request
                    <span class="transition duration-300 group-hover:translate-x-2" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <section id="reservation-form" data-gsap="section" data-reservation-content class="overflow-hidden bg-brand-ivory py-20 text-brand-ink sm:py-24 lg:py-32">
        <div class="mx-auto grid max-w-7xl gap-14 px-5 sm:px-6 lg:grid-cols-[0.78fr_1.22fr] lg:gap-20 lg:px-10 xl:gap-28">
            <aside data-reservation-sidebar class="lg:sticky lg:top-28 lg:self-start">
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.32em] text-brand-gold-dark">Reservation Request</p>
                <h2 data-gsap-reveal class="mt-5 max-w-lg font-display text-4xl leading-tight sm:text-5xl">An evening thoughtfully prepared</h2>
                <p data-gsap-reveal class="mt-6 max-w-xl text-base leading-8 text-brand-muted">
                    Tell us when you would like to join us and who will be dining. Every request receives the personal attention it deserves.
                </p>

                <div data-gsap-reveal class="mt-10 h-px w-16 bg-brand-gold"></div>

                <div class="mt-10 border-l border-brand-gold/45 pl-6">
                    <h3 data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-gold-dark">Your request, thoughtfully handled</h3>
                    <ol class="mt-6 grid gap-6 text-sm leading-7 text-brand-muted">
                        <li data-gsap-reveal data-reservation-process-step class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">01</span><span>Share your preferred date, time, and party size.</span></li>
                        <li data-gsap-reveal data-reservation-process-step class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">02</span><span>Our team reviews availability and your notes.</span></li>
                        <li data-gsap-reveal data-reservation-process-step class="grid grid-cols-[2rem_1fr] gap-3"><span class="font-display text-xl text-brand-gold-dark">03</span><span>We contact you directly to confirm the reservation.</span></li>
                    </ol>
                </div>

                <div data-gsap-reveal data-reservation-notice class="mt-10 border border-brand-gold/35 bg-brand-paper p-6 text-sm leading-7 text-brand-muted">
                    <p class="font-semibold text-brand-ink">Please note</p>
                    <p class="mt-2">This is a Reservation Request, not a confirmed reservation. Our team will manually review your request and contact you after reviewing availability.</p>
                </div>
            </aside>

            <div>
                @if (session()->has('status') || session()->has('success'))
                    <div data-reservation-feedback role="status" class="mb-6 border border-emerald-700/25 bg-emerald-50 p-5 text-sm leading-7 text-emerald-900">
                        {{ session('status') ?? session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div data-reservation-feedback role="alert" class="mb-6 border border-brand-burgundy/25 bg-red-50 p-5 text-sm leading-7 text-brand-burgundy">
                        <p class="font-semibold">Please review the highlighted fields and try again.</p>
                    </div>
                @endif

                <form data-gsap="panel" data-reservation-form method="POST" action="{{ route('reservation-requests.store') }}" class="border border-brand-gold/30 bg-white p-6 shadow-[0_28px_80px_rgba(23,25,22,0.1)] sm:p-9 lg:p-12" x-data="inquiryForm" @submit.prevent="submit" novalidate x-bind:aria-busy="submitting">
                    @csrf
                    <input type="hidden" name="source_page" value="reservation-request">

                    {{-- Honeypot: must remain empty. --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off" value="{{ old('website') }}">
                    </div>

                    <div class="border-b border-brand-gold/25 pb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your details</p>
                        <h2 class="mt-3 font-display text-3xl leading-tight text-brand-ink sm:text-4xl">Tell us about your preferred reservation</h2>
                        <p class="mt-4 text-sm leading-7 text-brand-muted">Fields marked with <span class="text-brand-burgundy">*</span> are required.</p>
                    </div>

                    @php
                        $inputClasses = 'mt-2 block min-h-12 w-full border border-stone-300 bg-brand-ivory px-4 py-3 text-base text-brand-ink placeholder:text-stone-400 transition hover:border-brand-gold-dark focus:border-brand-gold-dark focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/25';
                        $labelClasses = 'block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink';
                        $errorClasses = 'mt-2 text-sm text-brand-burgundy';
                    @endphp

                    <div class="mt-9 space-y-7">
                    <div data-gsap="panel" data-reservation-field-group="identity" class="grid gap-x-6 gap-y-7 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="{{ $labelClasses }}">Full name <span class="text-brand-burgundy">*</span></label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required class="{{ $inputClasses }}" placeholder="Juan dela Cruz">
                            @error('customer_name') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="{{ $labelClasses }}">Phone number <span class="text-brand-burgundy">*</span></label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required class="{{ $inputClasses }}" placeholder="+63 912 345 6789">
                            @error('phone') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div data-gsap="panel" data-reservation-field-group="contact" class="grid gap-x-6 gap-y-7 sm:grid-cols-2">
                        <div>
                            <label for="email" class="{{ $labelClasses }}">Email address <span class="text-brand-burgundy">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="{{ $inputClasses }}" placeholder="you@example.com">
                            @error('email') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="guest_count" class="{{ $labelClasses }}">Number of guests <span class="text-brand-burgundy">*</span></label>
                            <input id="guest_count" name="guest_count" type="number" min="1" max="200" step="1" value="{{ old('guest_count', 2) }}" required class="{{ $inputClasses }}">
                            @error('guest_count') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div data-gsap="panel" data-reservation-field-group="schedule" class="grid gap-x-6 gap-y-7 sm:grid-cols-2">
                        <div>
                            <label for="preferred_date" class="{{ $labelClasses }}">Preferred reservation date <span class="text-brand-burgundy">*</span></label>
                            <input id="preferred_date" name="preferred_date" type="date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" required class="{{ $inputClasses }}">
                            <p class="mt-2 text-xs leading-5 text-brand-muted">Your selected date is subject to manual availability review.</p>
                            @error('preferred_date') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="preferred_time" class="{{ $labelClasses }}">Preferred time <span class="text-brand-burgundy">*</span></label>
                            <input id="preferred_time" name="preferred_time" type="time" value="{{ old('preferred_time') }}" required class="{{ $inputClasses }}">
                            @error('preferred_time') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    </div>

                    <div data-gsap="panel" data-reservation-field-group="event" class="mt-8 border border-brand-gold/30 bg-brand-paper p-5 sm:p-6">
                        <input type="hidden" name="is_banquet_or_event" value="0">
                        <label class="flex cursor-pointer items-start gap-4 text-sm text-brand-muted">
                            <input type="checkbox" name="is_banquet_or_event" value="1" @checked(old('is_banquet_or_event')) class="mt-1 size-4 shrink-0 border-stone-400 bg-white text-brand-gold-dark focus:ring-brand-gold">
                            <span>
                                <span class="block font-semibold text-brand-ink">This is for a banquet or event inquiry</span>
                                <span class="mt-1 block leading-6">Select this for a larger gathering, private event, or banquet hall inquiry. Our team will follow up directly with next steps.</span>
                            </span>
                        </label>
                        @error('is_banquet_or_event') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                    </div>

                    <div data-gsap="panel" data-reservation-field-group="notes" class="mt-8">
                        <label for="special_requests" class="{{ $labelClasses }}">Special requests or notes</label>
                        <textarea id="special_requests" name="special_requests" rows="5" class="{{ $inputClasses }} resize-y" placeholder="Dietary needs, accessibility requests, seating preference, celebration details, or other notes">{{ old('special_requests') }}</textarea>
                        <p class="mt-2 text-xs leading-5 text-brand-muted">We will do our best to accommodate requests, but details are not guaranteed until confirmed by the restaurant.</p>
                        @error('special_requests') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                    </div>

                    <div data-gsap="panel" data-reservation-field-group="submit" class="mt-9 border-t border-brand-gold/25 pt-8">
                        <p class="max-w-xl text-sm leading-7 text-brand-muted">After submission, our team will review your request and contact you to confirm availability. This is not yet a confirmed reservation.</p>
                        <button type="submit" x-bind:disabled="submitting" class="mt-6 inline-flex min-h-12 w-full items-center justify-center bg-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:bg-brand-gold-dark disabled:cursor-wait disabled:opacity-60 focus-visible:outline-brand-gold sm:w-auto">
                            <span x-text="submitting ? 'Submitting…' : 'Request a Reservation'">Request a Reservation</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    </div>
</x-layouts.public>
