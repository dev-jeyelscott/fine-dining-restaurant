<x-layouts.public
    :title="$page?->meta_title ?: 'Gallery | Le Jardin Fine Dining'"
    :description="$page?->meta_description ?: 'Explore Le Jardin’s dining rooms, signature dishes, private celebrations, and beautifully considered details.'"
>
    @php
        $heroImage = $galleryImages->first();
        $supportingImages = $galleryImages->skip(1)->take(2)->values();
        $categories = $galleryImages
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();
    @endphp

    <div data-home-motion data-gallery-motion>
    <section data-public-hero class="relative isolate flex min-h-[44rem] items-center overflow-hidden bg-brand-ink lg:min-h-screen">
        @if ($heroImage?->image_url)
            <div data-gsap="hero-image" class="absolute inset-0 -z-30"><x-public.responsive-image
                :image="$heroImage"
                :alt="$heroImage->alt_text ?: $heroImage->title ?: 'Elegant restaurant dining experience'"
                variant="hero"
                sizes="100vw"
                width="1920"
                height="1280"
                loading="eager"
                fetchpriority="high"
                img-class="h-full w-full object-cover object-center"
            /></div>
        @else
            <div class="absolute inset-0 -z-30 bg-[radial-gradient(circle_at_70%_28%,rgba(201,164,93,0.25),transparent_34%),linear-gradient(145deg,#4d4437,#171916_68%)]"></div>
        @endif

        <div class="absolute inset-0 -z-20 bg-[linear-gradient(to_bottom,rgba(23,25,22,0.38),rgba(23,25,22,0.28)_32%,rgba(23,25,22,0.94))]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/88 via-brand-ink/35 to-transparent"></div>

        <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-36 sm:px-6 lg:px-10 lg:pb-28 lg:pt-44">
            <div data-gsap="hero-content" class="max-w-4xl">
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold sm:text-sm">
                    The Gallery
                </p>

                <h1 data-gsap-reveal class="mt-6 max-w-4xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-8xl">
                    {{ $page?->title ?: 'A Portrait of Le Jardin' }}
                </h1>

                <p data-gsap-reveal class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $page?->excerpt ?: 'Discover the atmosphere, culinary craft, and celebrations that define Le Jardin.' }}
                </p>

                <div data-gsap-reveal class="mt-10 flex flex-col gap-4 sm:flex-row">
                    <a
                        href="#gallery-collection"
                        class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition duration-300 hover:bg-brand-gold-dark hover:text-white"
                    >
                        View the Collection
                    </a>

                    <a
                        href="{{ route('banquet-hall') }}"
                        class="inline-flex min-h-12 items-center justify-center border border-white/60 px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:border-brand-gold hover:bg-brand-gold hover:text-brand-ink"
                    >
                        Explore Banquet Hall
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section data-gsap="section" class="overflow-hidden bg-brand-ivory py-24 text-brand-ink lg:py-32">
        <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-10">
            <div>
                <x-public.section-heading
                    eyebrow="The Experience"
                    title="Details worth remembering"
                    :description="$page?->content ?: 'From softly lit dining rooms to carefully finished plates and beautifully prepared celebrations, every image reflects our warmth, refinement, and attention to detail.'"
                    align="left"
                    theme="light"
                />

                <div data-gsap-reveal class="mt-10 grid max-w-xl grid-cols-2 gap-8 border-t border-brand-gold/35 pt-8">
                    <div data-gsap-counter data-count-value="{{ $galleryImages->count() }}">
                        <p data-gsap-count class="font-display text-4xl text-brand-ink">{{ $galleryImages->count() }}</p>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">
                            Moments on this page
                        </p>
                    </div>

                    <div data-gsap-counter data-count-value="{{ $categories->count() }}">
                        <p data-gsap-count class="font-display text-4xl text-brand-ink">{{ $categories->count() }}</p>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold-dark">
                            Categories on this page
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative min-h-[27rem] sm:min-h-[34rem]">
                <div data-gsap="frame" class="absolute left-0 top-0 h-[82%] w-[78%] border border-brand-gold/60" aria-hidden="true"></div>

                @if ($supportingImages->count() >= 2)
                    @foreach ($supportingImages as $image)
                        <figure data-gsap="image" @class([
                            'absolute overflow-hidden bg-brand-paper shadow-[0_28px_70px_rgba(23,25,22,0.18)]',
                            'left-5 top-5 h-[72%] w-[72%]' => $loop->first,
                            'bottom-0 right-0 h-[52%] w-[52%] border-8 border-brand-ivory' => $loop->last,
                        ])>
                            @if ($image->image_url)
                                <x-public.responsive-image
                                    :image="$image"
                                    :alt="$image->alt_text ?: $image->title ?: 'Restaurant gallery image'"
                                    variant="large"
                                    sizes="(min-width: 1024px) 40vw, 75vw"
                                    width="1200"
                                    height="900"
                                    img-class="h-full w-full object-cover"
                                />
                            @else
                                <div class="h-full w-full bg-[radial-gradient(circle_at_28%_22%,rgba(201,164,93,0.3),transparent_32%),linear-gradient(145deg,#d8cfbd,#8b7960)]"></div>
                            @endif
                        </figure>
                    @endforeach
                @elseif ($supportingImages->isNotEmpty())
                    @php
                        $supportingImage = $supportingImages->first();
                    @endphp

                    <figure data-gsap="image" class="absolute bottom-0 right-0 h-[88%] w-[88%] overflow-hidden bg-brand-paper shadow-[0_28px_70px_rgba(23,25,22,0.18)]">
                        @if ($supportingImage?->image_url)
                            <x-public.responsive-image
                                :image="$supportingImage"
                                :alt="$supportingImage->alt_text ?: $supportingImage->title ?: 'Restaurant gallery image'"
                                variant="large"
                                sizes="(min-width: 1024px) 45vw, 88vw"
                                width="1200"
                                height="900"
                                img-class="h-full w-full object-cover"
                            />
                        @else
                            <div class="h-full w-full bg-[radial-gradient(circle_at_28%_22%,rgba(201,164,93,0.3),transparent_32%),linear-gradient(145deg,#d8cfbd,#8b7960)]"></div>
                        @endif
                    </figure>
                @elseif ($heroImage?->image_url)
                    <figure data-gsap="image" class="absolute bottom-0 right-0 h-[88%] w-[88%] overflow-hidden bg-brand-paper shadow-[0_28px_70px_rgba(23,25,22,0.18)]">
                        <x-public.responsive-image
                            :image="$heroImage"
                            :alt="$heroImage->alt_text ?: $heroImage->title ?: 'Restaurant gallery image'"
                            variant="large"
                            sizes="(min-width: 1024px) 45vw, 88vw"
                            width="1200"
                            height="900"
                            img-class="h-full w-full object-cover"
                        />
                    </figure>
                @else
                    <div class="absolute bottom-0 right-0 h-[88%] w-[88%] bg-[radial-gradient(circle_at_65%_28%,rgba(201,164,93,0.3),transparent_34%),linear-gradient(145deg,#d8cfbd,#8b7960)] shadow-[0_28px_70px_rgba(23,25,22,0.18)]"></div>
                @endif
            </div>
        </div>
    </section>

    <section id="gallery-collection" data-gsap="gallery" class="scroll-mt-24 bg-brand-ink py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
            <x-public.section-heading
                eyebrow="The Collection"
                title="Ambiance, cuisine, and celebrations"
                description="Discover a considered collection of dining spaces, signature plates, private gatherings, and details that shape each occasion."
            />

            @if ($categories->isNotEmpty())
                <ul class="mt-10 flex flex-wrap justify-center gap-3" aria-label="Gallery categories">
                    @foreach ($categories as $category)
                        <li data-gsap-reveal class="border border-white/15 px-4 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-stone-300">
                            {{ $category }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($galleryImages->isNotEmpty())
                <div data-gsap="gallery-collection" class="mt-14 grid gap-4 md:auto-rows-[18rem] md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryImages as $image)
                        @php
                            $patternIndex = $loop->index % 8;
                            $cardClass = match ($patternIndex) {
                                0 => 'md:col-span-2 md:row-span-2 lg:col-span-2',
                                3 => 'lg:row-span-2',
                                5 => 'md:col-span-2 lg:col-span-1',
                                6 => 'lg:col-span-2',
                                default => '',
                            };
                        @endphp

                        <x-public.gallery-card
                            :image="$image"
                            variant="editorial"
                            :class="$cardClass"
                        />
                    @endforeach
                </div>

                @if ($galleryImages->hasPages())
                    <div data-gsap="section" class="mt-14 border-t border-white/10 pt-10">
                        {{ $galleryImages->links() }}
                    </div>
                @endif
            @else
                <x-public.alert type="warning" class="mt-14">
                    Our gallery is currently being curated. Please return soon for a glimpse of Le Jardin.
                </x-public.alert>
            @endif
        </div>
    </section>

    <section class="grid lg:grid-cols-2">
        <article data-gsap="panel" class="relative isolate flex min-h-[30rem] items-center overflow-hidden bg-brand-ink-soft px-5 py-20 sm:px-10 lg:px-16">
            @if ($heroImage?->image_url)
                <div data-gsap="parallax" class="absolute inset-0 -z-20"><x-public.responsive-image
                    :image="$heroImage"
                    alt=""
                    variant="large"
                    sizes="(min-width: 1024px) 50vw, 100vw"
                    width="1200"
                    height="900"
                    img-class="h-full w-full object-cover opacity-30"
                /></div>
            @endif
            <div class="absolute inset-0 -z-10 bg-brand-ink/78"></div>

            <div data-gsap-reveal class="mx-auto max-w-lg text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold">Private Celebrations</p>
                <h2 class="mt-5 font-display text-4xl leading-tight text-white sm:text-5xl">A setting worthy of the occasion</h2>
                <p class="mt-6 text-base leading-8 text-stone-300">
                    Discover an elegant banquet setting for family gatherings, private dining, celebrations, and corporate occasions.
                </p>
                <a
                    href="{{ route('banquet-hall') }}"
                    class="mt-9 inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white"
                >
                    Explore Banquet Hall
                </a>
            </div>
        </article>

        <article data-gsap="panel" class="relative isolate flex min-h-[30rem] items-center overflow-hidden bg-brand-paper px-5 py-20 text-brand-ink sm:px-10 lg:px-16">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_75%_22%,rgba(201,164,93,0.18),transparent_35%)]"></div>

            <div data-gsap-reveal class="mx-auto max-w-lg text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold-dark">Plan Your Visit</p>
                <h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl">Questions about the dining experience?</h2>
                <p class="mt-6 text-base leading-8 text-brand-muted">
                    Contact the restaurant team for directions, dining questions, private-event details, or other general inquiries.
                </p>
                <a
                    href="{{ route('contact.create') }}"
                    class="mt-9 inline-flex min-h-12 items-center justify-center bg-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ivory transition hover:bg-brand-ink-soft"
                >
                    Contact Us
                </a>
            </div>
        </article>
    </section>
    </div>
</x-layouts.public>
