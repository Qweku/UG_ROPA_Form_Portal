<?php

namespace App\Support;

class Security
{
    public static function safeExternalUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);
        $valid = filter_var($url, FILTER_VALIDATE_URL);
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if ($valid === false || ! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        return $url;
    }
}