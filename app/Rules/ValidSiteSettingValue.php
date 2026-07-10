<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class ValidSiteSettingValue implements ValidationRule
{
    /**
     * @var list<string>
     */
    private const HTTP_URL_KEYS = [
        'map_link',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
    ];

    public function __construct(private ?string $key)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        if ($this->key === 'email' && ! $this->isValidEmail($value)) {
            $fail('The :attribute must be a valid email address.');

            return;
        }

        if ($this->key === 'phone' && ! $this->isValidPhone($value)) {
            $fail('The :attribute may only contain digits, spaces, plus signs, hyphens, periods, and parentheses.');

            return;
        }

        if (in_array($this->key, self::HTTP_URL_KEYS, true) && ! $this->isValidHttpUrl($value)) {
            $fail('The :attribute must be a valid HTTP or HTTPS URL.');
        }
    }

    /**
     * @return list<string>
     */
    public static function publicLinkKeys(): array
    {
        return [
            'phone',
            'email',
            ...self::HTTP_URL_KEYS,
        ];
    }

    public static function accepts(?string $key, mixed $value): bool
    {
        $isValid = true;

        (new self($key))->validate(
            'value',
            $value,
            static function () use (&$isValid): void {
                $isValid = false;
            },
        );

        return $isValid;
    }

    private function isValidEmail(string $value): bool
    {
        return strlen($value) <= 254
            && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isValidPhone(string $value): bool
    {
        return strlen($value) <= 40
            && preg_match('/\A(?=.*[0-9])[0-9+().\- ]+\z/', $value) === 1;
    }

    private function isValidHttpUrl(string $value): bool
    {
        if (strlen($value) > 2048 || filter_var($value, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);

        return is_string($scheme)
            && in_array(strtolower($scheme), ['http', 'https'], true);
    }
}
