@props([
    'title' => null,
    'description' => null,
])

@php
    $siteName = \App\Models\SiteSetting::value('restaurant_name', config('app.name'));
    $pageTitle = $title ? "{$title} | {$siteName}" : $siteName;
    $metaDescription = $description ?: \App\Models\SiteSetting::value(
        'meta_description',
        'An elevated dining destination for considered cuisine, gracious hospitality, intimate celebrations, and memorable occasions.'
    );
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
