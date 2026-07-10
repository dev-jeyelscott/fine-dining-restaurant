@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'url' => null,
])

@php
    $siteName = \App\Models\SiteSetting::value('restaurant_name', config('app.name'));
    $pageTitle = $title ? "{$title} | {$siteName}" : $siteName;
    $metaDescription = $description ?: \App\Models\SiteSetting::value(
        'meta_description',
        'Fine dining restaurant offering menu, reservation requests, order inquiries, banquet hall information, gallery, and contact details.'
    );
    $canonicalUrl = $url ?: request()->url();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">

@if ($image)
    <meta property="og:image" content="{{ $image }}">
@endif
