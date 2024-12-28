<?php

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('robots');
});

it('serves default robots.txt content when no file exists', function () {
    config(['app.allow_robots' => true]);
    $response = $this->get('robots.txt');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('User-agent: *')
        ->assertSee('Disallow: /');
});

it('serves environment-specific robots.txt when available', function () {
    config(['app.allow_robots' => true]);
    $environment = app()->environment();
    $content = "User-agent: Googlebot\nAllow: /";

    Storage::disk('robots')->put("robots-{$environment}.txt", $content);

    $response = $this->get('robots.txt');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee($content);
});

it('falls back to default robots.txt when environment-specific file not found', function () {
    config(['app.allow_robots' => true]);
    $content = "User-agent: *\nAllow: /sitemap.xml";

    Storage::disk('robots')->put('robots.txt', $content);

    $response = $this->get('robots.txt');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee($content);
});

it('serves correct robots.txt based on environment', function () {
    config(['app.allow_robots' => true]);
    $environment = app()->environment();
    $envContent = "User-agent: Googlebot\nAllow: /";
    $defaultContent = "User-agent: *\nAllow: /sitemap.xml";

    // Create both files
    Storage::disk('robots')->put("robots-{$environment}.txt", $envContent);
    Storage::disk('robots')->put('robots.txt', $defaultContent);

    $response = $this->get('robots.txt');

    // Should serve environment-specific file
    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee($envContent)
        ->assertDontSee($defaultContent);
});

it('serves default robots.txt when allow_robots is false', function () {
    config(['app.allow_robots' => false]);

    $response = $this->get('robots.txt');

    $response->assertStatus(200)
        ->assertSee('User-agent: *')
        ->assertSee('Disallow: /');
});
