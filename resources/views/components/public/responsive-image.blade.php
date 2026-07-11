@props([
    'image' => null,
    'fallbackUrl' => null,
    'alt' => '',
    'variant' => 'card',
    'sizes' => '100vw',
    'width',
    'height',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'decoding' => 'async',
    'imgClass' => '',
])

@php
    $sourceUrl = $image?->responsiveImageUrl($variant) ?: $fallbackUrl;
    $sourceSet = $image?->responsiveImageSrcset();
@endphp

@if ($sourceUrl)
    <picture class="contents">
        @if ($sourceSet)
            <source
                type="image/jpeg"
                srcset="{{ $sourceSet }}"
                sizes="{{ $sizes }}"
            >
        @endif

        <img
            src="{{ $sourceUrl }}"
            @if ($sourceSet)
                srcset="{{ $sourceSet }}"
                sizes="{{ $sizes }}"
            @endif
            alt="{{ $alt }}"
            width="{{ $width }}"
            height="{{ $height }}"
            loading="{{ $loading }}"
            @if ($fetchpriority)
                fetchpriority="{{ $fetchpriority }}"
            @endif
            decoding="{{ $decoding }}"
            class="{{ $imgClass }}"
        >
    </picture>
@endif
