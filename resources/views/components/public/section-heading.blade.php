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
    $descriptionText = is_string($description) ? $description : (string) $description;
    $isRichDescription = strip_tags($descriptionText) !== $descriptionText;
@endphp

<div data-gsap-reveal {{ $attributes->except('class') }} @class([
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
        @if ($isRichDescription)
            <div @class([
                'mt-5 max-w-2xl space-y-4 text-base leading-8',
                'mx-auto' => $isCentered,
                '[&_a]:font-semibold [&_a]:underline [&_a]:underline-offset-4',
                '[&_blockquote]:border-l-2 [&_blockquote]:pl-5',
                '[&_h2]:mt-6 [&_h2]:font-display [&_h2]:text-2xl',
                '[&_h3]:mt-5 [&_h3]:font-display [&_h3]:text-xl',
                '[&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6',
                '[&_p]:leading-8',
                '[&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6',
                'text-brand-muted [&_a]:text-brand-gold-dark [&_blockquote]:border-brand-gold/50 [&_h2]:text-brand-ink [&_h3]:text-brand-ink [&_li]:marker:text-brand-gold-dark [&_strong]:text-brand-ink' => $isLight,
                'text-stone-400 [&_a]:text-brand-gold [&_blockquote]:border-brand-gold/50 [&_h2]:text-brand-ivory [&_h3]:text-brand-ivory [&_li]:marker:text-brand-gold [&_strong]:text-brand-ivory' => ! $isLight,
            ])>
                {!! str($descriptionText)->sanitizeHtml() !!}
            </div>
        @else
            <p @class([
                'mt-5 max-w-2xl text-base leading-8',
                'mx-auto' => $isCentered,
                'text-brand-muted' => $isLight,
                'text-stone-400' => ! $isLight,
            ])>
                {{ $description }}
            </p>
        @endif
    @endif

    <div @class([
        'mt-7 h-px w-14 bg-brand-gold',
        'mx-auto' => $isCentered,
    ]) aria-hidden="true"></div>
</div>
