<?php

namespace Netauratech\ThemeManager;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Netauratech\CoreCms\Http\Events\OptionUpdated;
use Netauratech\ThemeManager\Listeners\ClearThemeCache;
use Netauratech\ThemeManager\Services\ThemeAssetSource;
use Netauratech\ThemeManager\Services\ThemeManager;

class ThemeManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ThemeManager::class, function () {
            return new ThemeManager();
        });

        $this->app->tag(ThemeAssetSource::class, 'cms.asset.sources');
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $themeManager = $this->app->make(ThemeManager::class);
        $themePath = $themeManager->getThemePath();

        $this->app->events->listen(
            OptionUpdated::class,
            ClearThemeCache::class
        );

        $this->loadViewsFrom($themePath.'/views', 'theme');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}