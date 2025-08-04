<?php

namespace Netauratech\ThemeManager;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Netauratech\ThemeManager\Services\ThemeManager;

class ThemeManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // We bind the ThemeManager service to the container.
        // We'll give it the default theme name for now.
        $this->app->bind(ThemeManager::class, function () {
            return new ThemeManager('default');
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $themeManager = $this->app->make(ThemeManager::class);
        $themePath = $themeManager->getThemePath();

        $this->loadViewsFrom($themePath.'/views', 'theme');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}