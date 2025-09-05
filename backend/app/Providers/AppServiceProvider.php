<?php

namespace App\Providers;

use App\Domain\OpenAI\OpenAIClientInterface;
use App\Infrastructure\OpenAI\OpenAIChatClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OpenAIClientInterface::class, OpenAIChatClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
