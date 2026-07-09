@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div class="mx-auto max-w-3xl text-center">
    @if ($eyebrow)
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-300">
            {{ $eyebrow }}
        </p>
    @endif

    <h2 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-4xl">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="mt-4 text-base leading-7 text-stone-400">
            {{ $description }}
        </p>
    @endif
</div>
