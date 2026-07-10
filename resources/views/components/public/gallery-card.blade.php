@props([
    'image',
    'variant' => 'default',
])

@php
    $isEditorial = $variant === 'editorial';
@endphp

@if ($isEditorial)
    <article {{ $attributes->class([
        'group relative isolate min-h-72 overflow-hidden bg-brand-ink-soft',
    ]) }}>
        @if ($image->image_url)
            <img
                src="{{ $image->image_url }}"
                alt="{{ $image->alt_text ?: $image->title ?: 'Restaurant gallery image' }}"
                width="1200"
                height="900"
                class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_28%_22%,rgba(201,164,93,0.3),transparent_32%),linear-gradient(145deg,#4d4437,#171916)]"></div>
        @endif

        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-brand-ink/95 via-brand-ink/10 to-transparent opacity-85 transition duration-500 group-hover:opacity-100"></div>
        <div class="absolute inset-0 -z-10 ring-1 ring-inset ring-white/10 transition duration-500 group-hover:ring-brand-gold/55"></div>

        @if ($image->title || $image->category)
            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-7">
                @if ($image->category)
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.26em] text-brand-gold">
                        {{ $image->category }}
                    </p>
                @endif

                @if ($image->title)
                    <h3 class="mt-2 max-w-xl font-display text-2xl leading-tight text-white sm:text-3xl">
                        {{ $image->title }}
                    </h3>
                @endif
            </div>
        @endif
    </article>
@else
    <article {{ $attributes->class([
        'group overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]',
    ]) }}>
        @if ($image->image_url)
            <div class="overflow-hidden">
                <img
                    src="{{ $image->image_url }}"
                    alt="{{ $image->alt_text ?: $image->title ?: 'Restaurant gallery image' }}"
                    width="960"
                    height="720"
                    class="h-64 w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                    decoding="async"
                >
            </div>
        @endif

        @if ($image->title || $image->category)
            <div class="p-4">
                @if ($image->title)
                    <h3 class="font-semibold text-white">{{ $image->title }}</h3>
                @endif

                @if ($image->category)
                    <p class="mt-1 text-xs uppercase tracking-widest text-amber-300">
                        {{ $image->category }}
                    </p>
                @endif
            </div>
        @endif
    </article>
@endif
