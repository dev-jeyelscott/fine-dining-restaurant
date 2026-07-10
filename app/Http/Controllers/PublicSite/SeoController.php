<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return new Response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        $urls = [
            route('home'),
            route('menu'),
            route('reservation-request.create'),
            route('order-inquiry.create'),
            route('gallery'),
            route('banquet-hall'),
            route('contact.create'),
        ];

        $entries = implode("\n", array_map(
            static fn (string $url): string => '    <url><loc>'.e($url).'</loc></url>',
            $urls,
        ));

        $content = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$entries}
</urlset>
XML;

        return new Response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
