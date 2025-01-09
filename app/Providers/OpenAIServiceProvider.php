<?php

namespace App\Providers;

use App\Services\ImageReviewGeneration;
use Illuminate\Support\ServiceProvider;
use OpenAI;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register OpenAI client
        $this->app->singleton(OpenAI\Client::class, function () {
            return OpenAI::client(config('services.openai.api_key'));
        });

        // Register ImageReviewGeneration service
        $this->app->singleton(ImageReviewGeneration::class, function ($app) {
            return new ImageReviewGeneration(
                $app->make(OpenAI\Client::class)
            );
        });
    }
}
