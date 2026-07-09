<?php

use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Route;

it('does not register public form post routes before the workflow is implemented', function (string $uri) {
    $route = collect(Route::getRoutes())->first(
        fn (LaravelRoute $route): bool => in_array('POST', $route->methods(), true) && $route->uri() === $uri,
    );

    expect($route)->toBeNull();
})->with([
    'Reservation Request' => 'reservation-request',
    'Order Inquiry' => 'order-inquiry',
    'contact' => 'contact',
]);

it('only registers routes whose controller actions exist', function () {
    collect(Route::getRoutes())->each(function (LaravelRoute $route): void {
        $controller = $route->getAction('controller');

        if (! is_string($controller)) {
            return;
        }

        [$class, $method] = str_contains($controller, '@')
            ? explode('@', $controller, 2)
            : [$controller, '__invoke'];

        expect(method_exists($class, $method))
            ->toBeTrue("Route [{$route->uri()}] references missing {$class}::{$method}().");
    });
});
