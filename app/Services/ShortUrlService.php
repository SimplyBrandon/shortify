<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlService
{
    public static function encode(string $url, string $alias = null): ShortUrl
    {
        if(!$alias)
        {
            $alias = self::generateRandomAlias();
        }

        $encodedUrl = ShortUrl::create([
            'original_url' => $url,
            'alias' => $alias
        ]);

        return $encodedUrl;
    }

    public static function decode(string $alias): ?ShortUrl
    {
        $shortUrl = ShortUrl::where('alias', $alias)->first();

        if(!$shortUrl)
        {
            return null;
        }

        return $shortUrl;
    }

    public static function generateRandomAlias(): string
    {
        $alias = Str::random(6);

        while(ShortUrl::where('alias', $alias)->exists())
        {
            $alias = Str::random(6);
        }

        return $alias;
    }
}
