@props([
    'item',
])

<article class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
    @if ($item->image_url)
        <img
            src="{{ $item->image_url }}"
            alt="{{ $item->name }}"
            class="mb-4 h-48 w-full rounded-xl object-cover"
            loading="lazy"
        >
    @endif

    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-white">{{ $item->name }}</h3>

            @if ($item->category)
                <p class="mt-1 text-xs uppercase tracking-widest text-amber-300">
                    {{ $item->category->name }}
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
