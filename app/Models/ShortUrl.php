<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = ['original_url', 'alias'];
    protected $appends = ['short_url', 'domain', 'time_ago'];

    public function getShortUrlAttribute(): string
    {
        return env('APP_URL') . env('SHORT_URL_PATH', '/') . $this->alias;
    }

    public function getDomainAttribute(): string
    {
        return "https://" . parse_url($this->original_url, PHP_URL_HOST);
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
