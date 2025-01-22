<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    protected $testUrl = 'https://laravel.com';
    protected $testAlias = 'shortifytestexample';

    public function test_url_can_be_shortened_with_random_alias(): void
    {
        $response = $this->getJson('/api/encode?url=' . $this->testUrl);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'short_url'
        ]);

        $this->assertDatabaseHas('short_urls', [
            'original_url' => $this->testUrl
        ]);
    }

    public function test_url_can_be_shortened_with_custom_alias(): void
    {
        $response = $this->getJson('/api/encode?url=' . $this->testUrl . '&alias=' . $this->testAlias);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'short_url'
        ]);

        $this->assertDatabaseHas('short_urls', [
            'alias' => $this->testAlias,
            'original_url' => $this->testUrl
        ]);
    }

    public function test_existing_alias_returns_error(): void
    {
        ShortUrl::create([
            'alias' => $this->testAlias,
            'original_url' => $this->testUrl
        ]);

        $response = $this->getJson('/api/encode?url=' . $this->testUrl . '&alias=' . $this->testAlias);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_url_alias_can_be_resolved(): void
    {
        ShortUrl::create([
            'alias' => $this->testAlias,
            'original_url' => $this->testUrl
        ]);

        $response = $this->getJson('/api/decode?alias=' . $this->testAlias);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'original_url'
        ]);
    }

    public function test_invalid_url_alias_returns_error(): void
    {
        $response = $this->getJson('/api/decode?alias=' . Str::random(20));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_invalid_url_returns_error(): void
    {
        $response = $this->getJson('/api/encode?url=invalid-url');

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_short_url_redirects_to_original_url(): void
    {
        $shortUrl = ShortUrl::create([
            'alias' => $this->testAlias,
            'original_url' => $this->testUrl
        ]);

        $response = $this->get($shortUrl->short_url);
        $response->assertStatus(302);
        $response->assertRedirect($this->testUrl);
    }
}
