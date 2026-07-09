@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'primaryLabel' => null,
    'primaryUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
])

<section class="relative overflow-hidden bg-stone-950">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(251,191,36,0.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(120,53,15,0.22),transparent_40%)]"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="max-w-3xl">
            @if ($eyebrow)
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-300">
                    {{ $eyebrow }}
                </p>
            @endif

            <h1 class="mt-5 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                {{ $title }}
            </h1>

            @if ($description)
                <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                    {{ $description }}
                </p>
            @endif

            @if ($primaryLabel || $secondaryLabel)
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    @if ($primaryLabel && $primaryUrl)
                        <a
                            href="{{ $primaryUrl }}"
                            class="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-200"
                        >
                            {{ $primaryLabel }}
                        </a>
                    @endif

                    @if ($secondaryLabel && $secondaryUrl)
                        <a
                            href="{{ $secondaryUrl }}"
                            class="inline-flex items-center justify-center rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-stone-100 transition hover:border-amber-200 hover:text-amber-200"
                        >
                            {{ $secondaryLabel }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
