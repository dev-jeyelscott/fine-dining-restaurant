<?php

test('shared Alpine inquiry controller keeps page-specific success titles', function (): void {
    $script = file_get_contents(resource_path('js/forms/inquiry-form.js'));

    expect($script)
        ->not->toBeFalse()
        ->toContain('contact: \'Contact Inquiry received\'')
        ->toContain('\'order-inquiry\': \'Order Inquiry received\'')
        ->toContain('\'reservation-request\': \'Reservation Request received\'')
        ->toContain('resetAfterSuccess()')
        ->toContain('\'Unable to connect\'')
        ->toContain('\'Unable to process inquiry\'')
        ->toContain('aria-describedby');
});

test('public notification center provides distinct accessible success and error semantics', function (): void {
    $component = file_get_contents(resource_path('views/components/public/notification-center.blade.php'));

    expect($component)
        ->not->toBeFalse()
        ->toContain('notification?.type === \'success\' ? \'status\' : \'alert\'')
        ->toContain('notification?.type === \'success\' ? \'polite\' : \'assertive\'')
        ->toContain('aria-atomic="true"')
        ->toContain('@keydown.escape.window="close()"')
        ->toContain('aria-label="Dismiss notification"');
});

test('public stylesheet cloaks Alpine notifications before initialization', function (): void {
    $styles = file_get_contents(resource_path('css/public.css'));

    expect($styles)
        ->not->toBeFalse()
        ->toContain('[x-cloak]')
        ->toContain('display: none !important;');
});

test('public layout renders one shared notification center', function (): void {
    $layout = file_get_contents(resource_path('views/components/layouts/public.blade.php'));

    expect($layout)
        ->not->toBeFalse()
        ->and(substr_count($layout, '<x-public.notification-center />'))
        ->toBe(1);
});
