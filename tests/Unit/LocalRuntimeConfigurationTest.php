<?php

/**
 * @return array<string, mixed>
 */
function localRuntimeComposerConfiguration(): array
{
    $configuration = json_decode(
        file_get_contents(dirname(__DIR__, 2).'/composer.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    expect($configuration)->toBeArray();

    return $configuration;
}

test('example environment avoids sqlite backed request state by default', function (): void {
    $environment = parse_ini_file(
        dirname(__DIR__, 2).'/.env.example',
        false,
        INI_SCANNER_RAW,
    );

    expect($environment)->toBeArray()
        ->and($environment['DB_CONNECTION'] ?? null)->toBe('sqlite')
        ->and($environment['SESSION_DRIVER'] ?? null)->toBe('file')
        ->and($environment['CACHE_STORE'] ?? null)->toBe('file')
        ->and($environment['QUEUE_CONNECTION'] ?? null)->toBe('sync');
});

test('normal development command starts only the server and vite', function (): void {
    $configuration = localRuntimeComposerConfiguration();
    $developmentCommand = implode(' ', $configuration['scripts']['dev'] ?? []);

    expect($developmentCommand)
        ->toContain('php artisan serve')
        ->toContain('npm run dev')
        ->not->toContain('queue:listen')
        ->not->toContain('queue:work');
});

test('queue worker is opt in and uses queue work', function (): void {
    $configuration = localRuntimeComposerConfiguration();
    $queueCommand = implode(' ', $configuration['scripts']['dev:queue'] ?? []);

    expect($queueCommand)
        ->toContain('php artisan queue:work')
        ->toContain('--tries=1')
        ->not->toContain('queue:listen');
});
