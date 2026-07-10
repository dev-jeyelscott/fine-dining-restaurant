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
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 shadow-2xl shadow-black/20">
                <h2 class="text-2xl font-bold text-white">Restaurant details</h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Reach out directly or send us a message using the inquiry form. Our team will review your message and follow up as soon as practical.
                </p>

                <dl class="mt-6 space-y-5 text-sm">
                    @if ($settings['phone'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Phone</dt>
                            <dd class="mt-1 text-stone-400">
                                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}" class="hover:text-amber-200">
                                    {{ $settings['phone'] }}
                                </a>
                            </dd>
                        </div>
                    @endif

                    @if ($settings['email'] ?? null)
                        <div>
                            <dt class="font-semibold text-stone-300">Email</dt>
                            <dd class="mt-1 text-stone-400">
                                <a href="mailto:{{ $settings['email'] }}" class="hover:text-amber-200">
                                    {{ $settings['email'] }}
                                </a>
                            </dd>
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

                @if (($settings['facebook_url'] ?? null) || ($settings['instagram_url'] ?? null) || ($settings['tiktok_url'] ?? null))
                    <div class="mt-8 border-t border-white/10 pt-6">
                        <h3 class="text-sm font-semibold text-stone-300">Social links</h3>

                        <div class="mt-3 flex flex-wrap gap-3 text-sm">
                            @if ($settings['facebook_url'] ?? null)
                                <a
                                    href="{{ $settings['facebook_url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full border border-white/10 px-4 py-2 text-stone-300 hover:border-amber-200 hover:text-amber-200"
                                >
                                    Facebook
                                </a>
                            @endif

                            @if ($settings['instagram_url'] ?? null)
                                <a
                                    href="{{ $settings['instagram_url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full border border-white/10 px-4 py-2 text-stone-300 hover:border-amber-200 hover:text-amber-200"
                                >
                                    Instagram
                                </a>
                            @endif

                            @if ($settings['tiktok_url'] ?? null)
                                <a
                                    href="{{ $settings['tiktok_url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full border border-white/10 px-4 py-2 text-stone-300 hover:border-amber-200 hover:text-amber-200"
                                >
                                    TikTok
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-8 rounded-2xl border border-amber-300/20 bg-amber-300/10 p-4 text-sm leading-6 text-amber-100">
                    For reservations and order requests, please use the dedicated forms so our team receives the right details for manual review.
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 shadow-2xl shadow-black/20">
                <h2 class="text-2xl font-bold text-white">Send an inquiry</h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Send us a general question, banquet inquiry, or restaurant message. This form is for manual restaurant follow-up.
                </p>

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
                    action="{{ route('contact-inquiries.store') }}"
                    class="mt-8 space-y-6"
                    novalidate
                >
                    @csrf

                    <input type="hidden" name="source_page" value="contact">

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

                    <div class="grid gap-6 sm:grid-cols-2">
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
                            <label for="phone" class="block text-sm font-medium text-stone-200">
                                Phone number
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                                placeholder="+63 912 345 6789"
                            >
                            @error('phone')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-stone-200">
                            Subject
                        </label>
                        <select
                            id="subject"
                            name="subject"
                            class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                        >
                            <option value="">Select a topic</option>
                            <option value="General inquiry" @selected(old('subject') === 'General inquiry')>
                                General inquiry
                            </option>
                            <option value="Banquet inquiry" @selected(old('subject') === 'Banquet inquiry')>
                                Banquet inquiry
                            </option>
                            <option value="Private event inquiry" @selected(old('subject') === 'Private event inquiry')>
                                Private event inquiry
                            </option>
                            <option value="Menu question" @selected(old('subject') === 'Menu question')>
                                Menu question
                            </option>
                            <option value="Other" @selected(old('subject') === 'Other')>
                                Other
                            </option>
                        </select>
                        @error('subject')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-stone-200">
                            Message <span class="text-red-300">*</span>
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            required
                            class="mt-2 block w-full rounded-xl border border-white/10 bg-stone-950/60 px-4 py-3 text-white placeholder:text-stone-500 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/20"
                            placeholder="How can we help?"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-amber-300/20 bg-amber-300/10 p-4 text-sm leading-6 text-amber-100">
                        Your message will be reviewed by our team. For table requests or order inquiries, use the dedicated forms so the restaurant receives all required details.
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-5 text-stone-500">
                            Fields marked with <span class="text-red-300">*</span> are required.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-stone-950"
                        >
                            Send Inquiry
                        </button>
                    </div>
                </form>

                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('reservation-request.create') }}"
                        class="rounded-full border border-white/15 px-5 py-3 text-center text-sm font-semibold text-stone-100 hover:border-amber-200 hover:text-amber-200"
                    >
                        Request a Reservation
                    </a>

                    <a
                        href="{{ route('order-inquiry.create') }}"
                        class="rounded-full border border-white/15 px-5 py-3 text-center text-sm font-semibold text-stone-100 hover:border-amber-200 hover:text-amber-200"
                    >
                        Submit Order Inquiry
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
