<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = ['original_url', 'alias'];
    protected $appends = ['short_url'];

    public function getShortUrlAttribute()
    {
        return env('APP_URL') . env('SHORT_URL_PATH', '/') . $this->alias;
    }
}
