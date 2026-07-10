<x-layouts.public
    :title="$page?->meta_title ?: 'Banquet Hall'"
    :description="$page?->meta_description ?: 'Learn about our banquet hall, event space, capacity, and inquiry options.'"
>
    @php
        $featuredImage = $banquetImages->first();
        $galleryImages = $banquetImages->skip(1);
    @endphp

    <section data-banquet-hero data-public-hero class="relative isolate flex min-h-[44rem] items-center overflow-hidden bg-brand-ink lg:min-h-screen">
        @if ($featuredImage?->image_url)
            <x-public.responsive-image
                :image="$featuredImage"
                :alt="$featuredImage->alt_text ?: $featuredImage->title ?: 'Elegant banquet hall prepared for a special gathering'"
                variant="hero"
                sizes="100vw"
                width="1920"
                height="1280"
                loading="eager"
                fetchpriority="high"
                img-class="absolute inset-0 -z-30 h-full w-full object-cover object-center"
            />
        @else
            <div class="absolute inset-0 -z-30 bg-[radial-gradient(circle_at_68%_28%,rgba(201,164,93,0.25),transparent_30%),linear-gradient(135deg,#4d4437,#171916_68%)]"></div>
        @endif

        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.42),rgba(23,25,22,0.28)_32%,rgba(23,25,22,0.94))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/90 via-brand-ink/45 to-transparent"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-36 sm:px-6 lg:px-10 lg:pb-28 lg:pt-44">
            <div class="max-w-4xl">
                <p class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold sm:text-sm">Banquet Hall</p>
                <h1 class="mt-6 max-w-4xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-8xl">
                    {{ $page?->title ?: 'Celebrate meaningful gatherings' }}
                </h1>
                <p class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Explore our event space for birthdays, family celebrations, corporate meals, and special occasions.' }}
                </p>

                <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                    <a
                        href="{{ route('contact.create') }}"
                        class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition duration-300 hover:bg-brand-gold-dark hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold"
                    >
                        Contact Us
                    </a>
                    <a
                        href="#banquet-details"
                        class="inline-flex min-h-12 items-center justify-center border border-white/60 px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:border-brand-gold hover:bg-brand-gold hover:text-brand-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold"
                    >
                        Explore the Space
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="banquet-details" data-banquet-details class="overflow-hidden bg-brand-ivory py-24 text-brand-ink lg:py-32">
        <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-10">
            <div>
                <x-public.section-heading
                    eyebrow="Gather With Us"
                    title="A refined setting, made personal"
                    align="left"
                    theme="light"
                />

                <div class="mt-8 max-w-xl space-y-5 text-base leading-8 text-brand-muted [&_a]:font-semibold [&_a]:text-brand-gold-dark [&_a]:underline [&_a]:underline-offset-4 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:text-brand-ink [&_h3]:font-display [&_h3]:text-xl [&_h3]:text-brand-ink [&_ol]:list-decimal [&_ol]:pl-6 [&_strong]:text-brand-ink [&_ul]:list-disc [&_ul]:pl-6">
                    @if ($page?->content)
                        {!! str($page->content)->sanitizeHtml() !!}
                    @else
                        <p>Our banquet hall is suited for family celebrations, private dining, corporate meals, and other special gatherings.</p>
                        <p>Please contact our team for capacity, availability, menu options, and event details. All banquet inquiries are manually reviewed.</p>
                    @endif
                </div>

                <div class="mt-9 border-l border-brand-gold bg-white/55 px-6 py-5 text-sm leading-7 text-brand-muted sm:px-7">
                    <p class="font-semibold uppercase tracking-[0.18em] text-brand-gold-dark">Thoughtfully arranged</p>
                    <p class="mt-3">Banquet requests are inquiries only. Our team will review your preferred date, guest count, event type, and details before confirming availability.</p>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-2xl lg:mx-0">
                <div class="absolute -right-7 -top-7 hidden h-full w-full border border-brand-gold/70 lg:block" aria-hidden="true"></div>
                <div class="relative aspect-[4/3] overflow-hidden bg-brand-paper shadow-[0_30px_80px_rgba(23,25,22,0.18)]">
                    @if ($featuredImage?->image_url)
                        <x-public.responsive-image
                            :image="$featuredImage"
                            :alt="$featuredImage->alt_text ?: $featuredImage->title ?: 'Banquet hall interior'"
                            variant="large"
                            sizes="(min-width: 1024px) 50vw, 100vw"
                            width="1200"
                            height="900"
                            img-class="h-full w-full object-cover"
                        />
                    @else
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_68%_28%,rgba(201,164,93,0.32),transparent_32%),linear-gradient(145deg,#4d4437,#171916)]"></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($galleryImages->isNotEmpty())
        <section data-banquet-gallery class="bg-brand-ink py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
                <x-public.section-heading
                    eyebrow="The Space"
                    title="An atmosphere for every occasion"
                    description="Warm details, elegant surroundings, and a welcoming backdrop for the moments you gather to celebrate."
                />

                <div class="mt-14 grid auto-rows-[18rem] gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryImages as $image)
                        <x-public.gallery-card
                            :image="$image"
                            variant="editorial"
                            @class([
                                'md:row-span-2 lg:col-span-2' => $loop->first,
                            ])
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section data-banquet-inquiry class="relative isolate overflow-hidden bg-brand-paper py-24 text-brand-ink lg:py-32">
        @if ($featuredImage?->image_url)
            <x-public.responsive-image
                :image="$featuredImage"
                alt=""
                variant="large"
                sizes="100vw"
                width="1600"
                height="900"
                img-class="absolute inset-0 -z-20 h-full w-full object-cover opacity-15 grayscale"
            />
        @endif
        <div class="absolute inset-0 -z-10 bg-brand-paper/90"></div>

        <div class="mx-auto max-w-4xl px-5 text-center sm:px-6 lg:px-10">
            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-brand-gold-dark">Plan Your Occasion</p>
            <h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl lg:text-6xl">Let us shape the details with you</h2>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-brand-muted">
                Tell us about your preferred date, guest count, and occasion. Our team will discuss the space, menu options, and availability with you directly.
            </p>
            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a
                    href="{{ route('contact.create') }}"
                    class="inline-flex min-h-12 items-center justify-center bg-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ivory transition duration-300 hover:bg-brand-ink-soft focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold-dark"
                >
                    Start a Banquet Inquiry
                </a>
                <a
                    href="{{ route('reservation-request.create') }}"
                    class="inline-flex min-h-12 items-center justify-center border border-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition duration-300 hover:bg-brand-ink hover:text-brand-ivory focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold-dark"
                >
                    Request a Reservation
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
