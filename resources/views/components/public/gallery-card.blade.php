@props([
    'image',
])

<article class="overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
    @if ($image->image_url)
        <img
            src="{{ $image->image_url }}"
            alt="{{ $image->alt_text ?: $image->title ?: 'Restaurant gallery image' }}"
            class="h-64 w-full object-cover transition duration-300 hover:scale-105"
            loading="lazy"
        >
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
