@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'image' => null,
    'imageUrl' => null,
    'imageAlt' => '',
    'primaryLabel' => null,
    'primaryUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
])

<section data-public-hero class="relative isolate flex min-h-[44rem] items-center overflow-hidden bg-brand-ink lg:min-h-screen">
    <div data-gsap="hero-image" class="absolute inset-0 -z-30">
    <x-public.responsive-image
        :image="$image"
        :fallback-url="$imageUrl"
        :alt="$imageAlt"
        variant="hero"
        sizes="100vw"
        width="1920"
        height="1280"
        loading="eager"
        fetchpriority="high"
        img-class="h-full w-full object-cover object-center"
    />
    </div>

    <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_70%_35%,rgba(201,164,93,0.14),transparent_36%),linear-gradient(to_bottom,rgba(23,25,22,0.82),rgba(23,25,22,0.46)_45%,rgba(23,25,22,0.92))]"></div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-brand-ink/85 via-brand-ink/35 to-transparent"></div>

    <div class="mx-auto w-full max-w-7xl px-5 pb-20 pt-36 sm:px-6 lg:px-10 lg:pb-28 lg:pt-44">
        <div data-gsap="hero-content" class="max-w-4xl">
            @if ($eyebrow)
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.38em] text-brand-gold sm:text-sm">
                    {{ $eyebrow }}
                </p>
            @endif

            <h1 data-gsap-reveal class="mt-6 max-w-4xl font-display text-5xl leading-[0.98] text-white sm:text-6xl lg:text-8xl">
                {{ $title }}
            </h1>

            @if ($description)
                <p data-gsap-reveal class="mt-7 max-w-2xl text-base leading-8 text-stone-200 sm:text-lg">
                    {{ $description }}
                </p>
            @endif

            @if ($primaryLabel || $secondaryLabel)
                <div data-gsap-reveal class="mt-10 flex flex-col gap-4 sm:flex-row">
                    @if ($primaryLabel && $primaryUrl)
                        <a
                            href="{{ $primaryUrl }}"
                            class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition duration-300 hover:bg-brand-gold-dark hover:text-white"
                        >
                            {{ $primaryLabel }}
                        </a>
                    @endif

                    @if ($secondaryLabel && $secondaryUrl)
                        <a
                            href="{{ $secondaryUrl }}"
                            class="inline-flex min-h-12 items-center justify-center border border-white/60 px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-300 hover:border-brand-gold hover:bg-brand-gold hover:text-brand-ink"
                        >
                            {{ $secondaryLabel }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <a
        href="#restaurant-story"
        class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-3 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-white/65 transition hover:text-brand-gold md:flex"
    >
        Discover
        <span data-gsap="discover-line" class="h-12 w-px bg-gradient-to-b from-white/70 to-transparent" aria-hidden="true"></span>
    </a>
</section>
