@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'center',
    'theme' => 'dark',
])

@php
    $isCentered = $align === 'center';
    $isLight = $theme === 'light';
@endphp

<div @class([
    'max-w-3xl',
    'mx-auto text-center' => $isCentered,
])>
    @if ($eyebrow)
        <p @class([
            'text-xs font-semibold uppercase tracking-[0.32em]',
            'text-brand-gold-dark' => $isLight,
            'text-brand-gold' => ! $isLight,
        ])>
            {{ $eyebrow }}
        </p>
    @endif

    <h2 @class([
        'mt-4 font-display text-4xl leading-tight sm:text-5xl',
        'text-brand-ink' => $isLight,
        'text-brand-ivory' => ! $isLight,
    ])>
        {{ $title }}
    </h2>

    @if ($description)
        <p @class([
            'mt-5 max-w-2xl text-base leading-8',
            'mx-auto' => $isCentered,
            'text-brand-muted' => $isLight,
            'text-stone-400' => ! $isLight,
        ])>
            {{ $description }}
        </p>
    @endif

    <div @class([
        'mt-7 h-px w-14 bg-brand-gold',
        'mx-auto' => $isCentered,
    ]) aria-hidden="true"></div>
</div>
