<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShortUrlService
{
    public static function encode(string $url, string $alias = null): ShortUrl
    {
        if(!$alias) {
            $alias = self::generateRandomAlias();
        }

        try {
            $encodedUrl = ShortUrl::create([
                'original_url' => $url,
                'alias' => $alias
            ]);
        } catch (\Exception $e) {
            Log::error('ShortUrlService@encode: ', $e->getMessage());
            return null;
        }

        return $encodedUrl;
    }

    public static function decode(string $alias): ?ShortUrl
    {
        try {
            $shortUrl = ShortUrl::where('alias', $alias)->first();
        } catch (ModelNotFoundException $e) {
            Log::error('ShortUrlService@decode: ', $e->getMessage());
            return null;
        }

        if(!$shortUrl) {
            return null;
        }

        return $shortUrl;
    }

    public static function generateRandomAlias(): string
    {
        $alias = Str::random(6);

        while(ShortUrl::where('alias', $alias)->exists()) {
            $alias = Str::random(6);
        }

        return $alias;
    }
}
