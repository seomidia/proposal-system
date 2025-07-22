<?php

namespace App\Services;

class JwtService
{
    public static function encode(array $payload, string $secret, string $alg = 'HS256'): string
    {
        $header = ['typ' => 'JWT', 'alg' => $alg];
        $segments = [];
        $segments[] = self::base64UrlEncode(json_encode($header));
        $segments[] = self::base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', implode('.', $segments), $secret, true);
        $segments[] = self::base64UrlEncode($signature);
        return implode('.', $segments);
    }

    public static function decode(string $jwt, string $secret): ?array
    {
        $segments = explode('.', $jwt);
        if (count($segments) !== 3) {
            return null;
        }

        [$header64, $payload64, $signature64] = $segments;
        $signature = self::base64UrlDecode($signature64);
        $expected = hash_hmac('sha256', $header64 . '.' . $payload64, $secret, true);

        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $payload = json_decode(self::base64UrlDecode($payload64), true);
        return is_array($payload) ? $payload : null;
    }

    protected static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected static function base64UrlDecode(string $data): string
    {
        $decoded = strtr($data, '-_', '+/');
        return base64_decode($decoded);
    }
}
