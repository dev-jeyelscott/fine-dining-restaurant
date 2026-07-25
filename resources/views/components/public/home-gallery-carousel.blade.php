@props(['galleryImages'])

<div data-home-gallery class="mt-14" role="region" aria-roledescription="carousel" aria-label="Restaurant gallery preview">
    @if ($galleryImages->isNotEmpty())
        @php($hasMultipleImages = $galleryImages->count() > 1)
        <div data-home-gallery-viewport class="relative mx-auto aspect-[4/3] w-full max-w-[1080px] overflow-x-clip overflow-y-visible sm:aspect-[16/10] lg:aspect-[16/9]">
            <div data-home-gallery-track class="relative h-full w-full touch-pan-y focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold" tabindex="0">
                @foreach ($galleryImages as $image)
                    <article data-home-gallery-slide data-index="{{ $loop->index }}" @class([
                        'absolute inset-y-[8%] left-1/2 h-[84%] w-[92%] -translate-x-1/2 overflow-hidden rounded-sm bg-brand-ink-soft shadow-2xl shadow-black/40 sm:w-[84%] lg:w-[78%] lg:max-w-[1080px]',
                        'z-20' => $loop->first,
                        'z-10' => $hasMultipleImages && $loop->index === 1,
                        'z-0' => $hasMultipleImages && $loop->index > 1,
                    ]) aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}" aria-label="{{ $loop->iteration }} of {{ $galleryImages->count() }}">
                        <div data-home-gallery-visual class="relative h-full w-full bg-brand-ink-soft">
                            @if ($image->image_url)
                                <x-public.responsive-image :image="$image" :alt="$image->alt_text ?: $image->title ?: 'Restaurant gallery image'" variant="large" sizes="(min-width: 1024px) 62vw, (min-width: 640px) 76vw, 92vw" width="1600" height="900" img-class="h-full w-full object-cover" />
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-ink/90 via-brand-ink/10 to-transparent"></div>
                            @if ($image->title || $image->category)
                                <div data-home-gallery-caption class="absolute inset-x-0 bottom-0 p-6 sm:p-9">
                                    @if ($image->category)<p class="text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-brand-gold">{{ $image->category }}</p>@endif
                                    @if ($image->title)<h3 class="mt-2 font-display text-3xl text-white sm:text-4xl">{{ $image->title }}</h3>@endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
        @if ($hasMultipleImages)
            <div data-home-gallery-controls class="mx-auto mt-6 flex max-w-[1080px] items-center justify-between gap-6 border-t border-brand-gold/25 pt-5">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-gold" aria-live="polite"><span data-home-gallery-current>01</span><span class="text-white/35"> / <span data-home-gallery-total>{{ str_pad((string) $galleryImages->count(), 2, '0', STR_PAD_LEFT) }}</span></span></p>
                <div class="flex gap-2"><button type="button" data-home-gallery-previous aria-label="Previous gallery image" class="inline-flex size-12 items-center justify-center border border-brand-gold/50 text-brand-gold transition hover:bg-brand-gold hover:text-brand-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold">&larr;</button><button type="button" data-home-gallery-next aria-label="Next gallery image" class="inline-flex size-12 items-center justify-center border border-brand-gold/50 text-brand-gold transition hover:bg-brand-gold hover:text-brand-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold">&rarr;</button></div>
            </div>
        @endif
    @else
        <x-public.alert type="warning">Our gallery is currently being curated. Please return soon for a glimpse of Le Jardin.</x-public.alert>
    @endif
</div>
