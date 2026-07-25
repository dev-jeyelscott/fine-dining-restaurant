<?php

test('example environment does not define usable default admin credentials', function (): void {
    $environment = parse_ini_file(
        dirname(__DIR__, 2).'/.env.example',
        false,
        INI_SCANNER_RAW,
    );

    expect($environment)->toBeArray()
        ->and($environment['ADMIN_USER_NAME'] ?? null)->toBe('')
        ->and($environment['ADMIN_USER_EMAIL'] ?? null)->toBe('')
        ->and($environment['ADMIN_USER_PASSWORD'] ?? null)->toBe('');
});
