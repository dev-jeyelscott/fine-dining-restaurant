<x-layouts.public
    :title="$page?->meta_title ?: 'Contact'"
    :description="$page?->meta_description ?: 'Contact us for reservations, order inquiries, banquet inquiries, location, and general questions.'"
>
    <section data-contact-hero class="relative isolate flex min-h-[38rem] items-end overflow-hidden bg-brand-ink sm:min-h-[44rem] lg:min-h-[48rem]">
        @if ($heroImage)
            <x-public.responsive-image
                :image="$heroImage"
                :alt="$heroImage->alt_text ?: $heroImage->title ?: 'Elegant restaurant interior'"
                variant="hero"
                sizes="100vw"
                width="1920"
                height="1280"
                loading="eager"
                fetchpriority="high"
                img-class="absolute inset-0 -z-30 h-full w-full object-cover object-center"
            />
        @else
            <div data-contact-hero-fallback class="absolute inset-0 -z-30 bg-[radial-gradient(circle_at_70%_25%,rgba(201,164,93,0.3),transparent_26%),linear-gradient(135deg,#353126,#171916_68%)]"></div>
        @endif

        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.5),rgba(23,25,22,0.42)_35%,rgba(23,25,22,0.96))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/85 via-brand-ink/30 to-transparent"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-16 pt-40 sm:px-6 sm:pb-20 lg:px-10 lg:pb-24">
            <div class="max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[0.36em] text-brand-gold sm:text-sm">A thoughtful welcome awaits</p>
                <h1 class="mt-5 font-display text-5xl leading-[1.02] text-white sm:text-6xl lg:text-7xl">{{ $page?->title ?: 'Get in touch' }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">{{ $page?->excerpt ?: 'Whether you have a question, are planning a gathering, or simply wish to reach us, our team is here to help.' }}</p>
                <a href="#contact-inquiry" class="group mt-9 inline-flex items-center gap-4 text-xs font-semibold uppercase tracking-[0.22em] text-white transition hover:text-brand-gold">
                    Send us a message
                    <span class="transition duration-300 group-hover:translate-x-2" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <section id="contact-inquiry" class="overflow-hidden bg-brand-ivory py-20 text-brand-ink sm:py-24 lg:py-32">
        @php
            $phone = $settings['phone'] ?? null;
            $phoneDigits = is_string($phone) ? preg_replace('/\D+/', '', $phone) : null;
            $phoneTelTarget = is_string($phone) && is_string($phoneDigits) && $phoneDigits !== ''
                ? (str_starts_with(ltrim($phone), '+') ? '+' : '').$phoneDigits
                : null;
            $inputClasses = 'mt-2 block min-h-12 w-full border border-stone-300 bg-brand-ivory px-4 py-3 text-base text-brand-ink placeholder:text-stone-400 transition hover:border-brand-gold-dark focus:border-brand-gold-dark focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/25';
            $labelClasses = 'block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink';
            $errorClasses = 'mt-2 text-sm text-brand-burgundy';
        @endphp

        <div class="mx-auto grid max-w-7xl gap-14 px-5 sm:px-6 lg:grid-cols-[0.78fr_1.22fr] lg:gap-20 lg:px-10 xl:gap-28">
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-brand-gold-dark">Contact us</p>
                <h2 class="mt-5 max-w-lg font-display text-4xl leading-tight sm:text-5xl">A warm response, thoughtfully given</h2>
                <p class="mt-6 max-w-xl text-base leading-8 text-brand-muted">Reach out directly or send us a message. Our team will personally review your inquiry and follow up as soon as practical.</p>
                <div class="mt-10 h-px w-16 bg-brand-gold"></div>

                <dl class="mt-10 grid gap-7 border-l border-brand-gold/45 pl-6 text-sm leading-7">
                    @if ($phoneTelTarget !== null)
                        <div><dt class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">Phone</dt><dd class="mt-1 text-brand-muted"><a href="tel:{{ $phoneTelTarget }}" class="transition hover:text-brand-ink">{{ $phone }}</a></dd></div>
                    @endif
                    @if ($settings['email'] ?? null)
                        <div><dt class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">Email</dt><dd class="mt-1 break-words text-brand-muted"><a href="mailto:{{ $settings['email'] }}" class="transition hover:text-brand-ink">{{ $settings['email'] }}</a></dd></div>
                    @endif
                    @if ($settings['address'] ?? null)
                        <div><dt class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">Visit us</dt><dd class="mt-1 text-brand-muted">{{ $settings['address'] }}</dd></div>
                    @endif
                    @if ($settings['map_link'] ?? null)
                        <div><dt class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">Directions</dt><dd class="mt-1"><a href="{{ $settings['map_link'] }}" target="_blank" rel="noopener noreferrer" class="group inline-flex items-center gap-3 font-semibold text-brand-ink transition hover:text-brand-gold-dark">Open location map <span class="transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span></a></dd></div>
                    @endif
                </dl>

                @if (($settings['facebook_url'] ?? null) || ($settings['instagram_url'] ?? null) || ($settings['tiktok_url'] ?? null))
                    <div class="mt-10 border-t border-brand-gold/25 pt-7">
                        <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">Follow our table</h3>
                        <div class="mt-4 flex flex-wrap gap-x-6 gap-y-3 text-sm font-semibold text-brand-ink">
                            @foreach (['facebook_url' => 'Facebook', 'instagram_url' => 'Instagram', 'tiktok_url' => 'TikTok'] as $settingKey => $label)
                                @if ($settings[$settingKey] ?? null)
                                    <a href="{{ $settings[$settingKey] }}" target="_blank" rel="noopener noreferrer" class="border-b border-brand-gold/50 pb-1 transition hover:border-brand-gold-dark hover:text-brand-gold-dark">{{ $label }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-10 border border-brand-gold/35 bg-brand-paper p-6 text-sm leading-7 text-brand-muted">
                    <p class="font-semibold text-brand-ink">Planning a visit?</p>
                    <p class="mt-2">For reservations and order requests, please use the dedicated forms so our team receives the right details for manual review.</p>
                    <div class="mt-5 grid gap-3 text-xs font-semibold uppercase tracking-[0.14em] text-brand-ink sm:flex sm:flex-wrap sm:gap-x-6">
                        <a href="{{ route('reservation-request.create') }}" class="transition hover:text-brand-gold-dark">Reservation Request</a>
                        <a href="{{ route('order-inquiry.create') }}" class="transition hover:text-brand-gold-dark">Order Inquiry</a>
                    </div>
                </div>
            </aside>

            <div>
                @if (session()->has('status') || session()->has('success'))
                    <div role="status" class="mb-6 border border-emerald-700/25 bg-emerald-50 p-5 text-sm leading-7 text-emerald-900">{{ session('status') ?? session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mb-6 border border-brand-burgundy/25 bg-red-50 p-5 text-sm leading-7 text-brand-burgundy"><p class="font-semibold">Please review the highlighted fields and try again.</p></div>
                @endif

                <form data-contact-form method="POST" action="{{ route('contact-inquiries.store') }}" class="border border-brand-gold/30 bg-white p-6 shadow-[0_28px_80px_rgba(23,25,22,0.1)] sm:p-9 lg:p-12" novalidate>
                    @csrf
                    <input type="hidden" name="source_page" value="contact">
                    <div class="hidden" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off" value="{{ old('website') }}"></div>

                    <div class="border-b border-brand-gold/25 pb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-gold-dark">Your message</p>
                        <h2 class="mt-3 font-display text-3xl leading-tight text-brand-ink sm:text-4xl">How may we assist you?</h2>
                        <p class="mt-4 text-sm leading-7 text-brand-muted">Send a general question, banquet inquiry, or restaurant message. Fields marked with <span class="text-brand-burgundy">*</span> are required.</p>
                    </div>

                    <div class="mt-9 grid gap-x-6 gap-y-7 sm:grid-cols-2">
                        <div class="sm:col-span-2"><label for="customer_name" class="{{ $labelClasses }}">Full name <span class="text-brand-burgundy">*</span></label><input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required class="{{ $inputClasses }}" placeholder="Juan dela Cruz">@error('customer_name') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror</div>
                        <div><label for="email" class="{{ $labelClasses }}">Email address <span class="text-brand-burgundy">*</span></label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="{{ $inputClasses }}" placeholder="you@example.com">@error('email') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror</div>
                        <div><label for="phone" class="{{ $labelClasses }}">Phone number</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="{{ $inputClasses }}" placeholder="+63 912 345 6789">@error('phone') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror</div>
                        <div class="sm:col-span-2"><label for="subject" class="{{ $labelClasses }}">Subject</label><select id="subject" name="subject" class="{{ $inputClasses }}"><option value="">Select a topic</option>@foreach (['General inquiry', 'Banquet inquiry', 'Private event inquiry', 'Menu question', 'Other'] as $subject)<option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>@endforeach</select>@error('subject') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror</div>
                        <div class="sm:col-span-2"><label for="message" class="{{ $labelClasses }}">Message <span class="text-brand-burgundy">*</span></label><textarea id="message" name="message" rows="7" required class="{{ $inputClasses }} resize-y" placeholder="How can we help?">{{ old('message') }}</textarea>@error('message') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror</div>
                    </div>

                    <div class="mt-9 border-t border-brand-gold/25 pt-8">
                        <p class="max-w-xl text-sm leading-7 text-brand-muted">Your message will be reviewed by our team. For table requests or order inquiries, use the dedicated forms so the restaurant receives all required details.</p>
                        <button type="submit" class="mt-6 inline-flex min-h-12 w-full items-center justify-center bg-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:bg-brand-gold-dark focus-visible:outline-brand-gold sm:w-auto">Send Inquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.public>
