@props([
    'item',
    'variant' => 'default',
])

@php
    $menuCategory = $item->relationLoaded('menuCategory')
        ? $item->menuCategory
        : null;
@endphp

@if ($variant === 'luxury')
    <article class="group">
        <div class="relative aspect-[4/5] overflow-hidden bg-brand-ink-soft">
            @if ($item->image_url)
                <img
                    src="{{ $item->image_url }}"
                    alt="{{ $item->name }}"
                    width="720"
                    height="900"
                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(201,164,93,0.22),transparent_32%),linear-gradient(145deg,#2b2e28,#171916)]"></div>
                <div class="absolute inset-x-6 bottom-6 border-t border-white/15 pt-4 text-xs uppercase tracking-[0.24em] text-white/55">
                    Image coming soon
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-brand-ink/45 via-transparent to-transparent transition duration-500 group-hover:from-brand-ink/20"></div>
        </div>

        <div class="pt-6">
            @if ($menuCategory)
                <p class="text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-brand-gold">
                    {{ $menuCategory->name }}
                </p>
            @endif

            <div class="mt-3 flex items-start justify-between gap-5">
                <h3 class="font-display text-2xl leading-tight text-brand-ivory transition group-hover:text-brand-gold">
                    {{ $item->name }}
                </h3>

                @if (! is_null($item->price))
                    <p class="shrink-0 text-sm font-semibold text-brand-gold">
                        ₱{{ number_format((float) $item->price, 2) }}
                    </p>
                @endif
            </div>

            @if ($item->description)
                <p class="mt-3 text-sm leading-7 text-stone-400">
                    {{ $item->description }}
                </p>
            @endif
        </div>
    </article>
@else
    <article class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
        @if ($item->image_url)
            <img
                src="{{ $item->image_url }}"
                alt="{{ $item->name }}"
                width="720"
                height="480"
                class="mb-4 h-48 w-full rounded-xl object-cover"
                loading="lazy"
                decoding="async"
            >
        @endif

        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-white">{{ $item->name }}</h3>

                @if ($menuCategory)
                    <p class="mt-1 text-xs uppercase tracking-widest text-amber-300">
                        {{ $menuCategory->name }}
                    </p>
                @endif
            </div>

            @if (! is_null($item->price))
                <p class="shrink-0 font-semibold text-amber-200">
                    ₱{{ number_format((float) $item->price, 2) }}
                </p>
            @endif
        </div>

        @if ($item->description)
            <p class="mt-3 text-sm leading-6 text-stone-400">
                {{ $item->description }}
            </p>
        @endif
    </article>
@endif
