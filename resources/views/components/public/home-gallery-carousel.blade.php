@props(['galleryImages'])

<div
    data-home-gallery
    aria-label="Restaurant gallery preview"
    class="mt-14 overflow-hidden"
>
    @if ($galleryImages->isNotEmpty())
        <div data-home-gallery-viewport class="overflow-hidden">
            <div data-home-gallery-track class="flex items-stretch gap-4 py-3 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold sm:gap-6" tabindex="0">
                @foreach ($galleryImages as $image)
                    <article
                        data-home-gallery-slide
                        @class([
                            'relative min-w-[86vw] overflow-hidden bg-brand-ink-soft sm:min-w-[74vw] lg:min-w-[58vw] lg:max-w-[920px]',
                            'aspect-[4/5] sm:aspect-[16/10]',
                        ])
                        aria-current="{{ $loop->first ? 'true' : 'false' }}"
                        aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                    >
                        <div data-home-gallery-visual class="relative h-full w-full overflow-hidden bg-brand-ink-soft opacity-55 scale-[0.9] transition-none">
                            @if ($image->image_url)
                                <div data-home-gallery-image class="h-full w-full">
                                    <x-public.responsive-image
                                        :image="$image"
                                        :alt="$image->alt_text ?: $image->title ?: 'Restaurant gallery image'"
                                        variant="large"
                                        sizes="(min-width: 1024px) 58vw, (min-width: 640px) 74vw, 86vw"
                                        width="1600"
                                        height="1000"
                                        img-class="h-full w-full object-cover"
                                    />
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-ink/90 via-brand-ink/10 to-transparent"></div>
                            @if ($image->title || $image->category)
                                <div data-home-gallery-caption class="absolute inset-x-0 bottom-0 overflow-hidden p-6 sm:p-9">
                                    <div class="translate-y-full">
                                        @if ($image->category)
                                            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-brand-gold">{{ $image->category }}</p>
                                        @endif
                                        @if ($image->title)
                                            <h3 class="mt-2 font-display text-3xl text-white sm:text-4xl">{{ $image->title }}</h3>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        @if ($galleryImages->count() > 1)
            <div data-home-gallery-controls class="mt-6 flex items-center justify-between gap-6 border-t border-brand-gold/25 pt-5">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-gold" aria-live="polite">
                    <span data-home-gallery-current>01</span>
                    <span class="text-white/35"> / <span data-home-gallery-total>{{ str_pad((string) $galleryImages->count(), 2, '0', STR_PAD_LEFT) }}</span></span>
                </p>
                <div class="flex gap-2">
                    <button type="button" data-home-gallery-previous aria-label="Previous gallery image" class="inline-flex size-12 items-center justify-center border border-brand-gold/50 text-brand-gold transition hover:bg-brand-gold hover:text-brand-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold">&larr;</button>
                    <button type="button" data-home-gallery-next aria-label="Next gallery image" class="inline-flex size-12 items-center justify-center border border-brand-gold/50 text-brand-gold transition hover:bg-brand-gold hover:text-brand-ink focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-gold">&rarr;</button>
                </div>
            </div>
        @endif
    @else
        <x-public.alert type="warning">
            Our gallery is currently being curated. Please return soon for a glimpse of Le Jardin.
        </x-public.alert>
    @endif
</div>
