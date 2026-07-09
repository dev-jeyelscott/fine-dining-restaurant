@props([
    'type' => 'info',
])

@php
    $classes = [
        'info' => 'border-blue-300/30 bg-blue-400/10 text-blue-100',
        'success' => 'border-emerald-300/30 bg-emerald-400/10 text-emerald-100',
        'warning' => 'border-amber-300/30 bg-amber-400/10 text-amber-100',
        'error' => 'border-red-300/30 bg-red-400/10 text-red-100',
    ][$type] ?? 'border-white/10 bg-white/5 text-stone-100';
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border p-4 text-sm leading-6 {$classes}"]) }}>
    {{ $slot }}
</div>
