<?php

namespace Netauratech\ThemeManager;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Netauratech\CoreCms\Events\LangLoaded;
use Netauratech\CoreCms\Events\OptionUpdated;
use Netauratech\CoreCms\Services\Admin\MenuManager;
use Netauratech\CoreCms\Services\AssetManager;
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
    public function boot(MenuManager $menuManager, AssetManager $assetManager): void
    {
        $themeManager = $this->app->make(ThemeManager::class);
        $themePath = $themeManager->getThemePath();

        $this->app->events->listen(
            OptionUpdated::class,
            ClearThemeCache::class
        );

        // Load all views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'theme');
        $this->loadViewsFrom($themePath.'/views', 'theme');

        $assetManager->registerTranslationPath('theme-manager', __DIR__.'/lang');
        $assetManager->registerTranslationPath('theme', $themePath.'/lang');

        // Lang
        $this->loadTranslationsFrom(__DIR__.'/lang', 'theme-manager');
        $this->loadTranslationsFrom($themePath.'/lang', 'theme');
        LangLoaded::dispatch('theme-manager');

        // Allows you to publish translations of the package
        $this->publishes([
            __DIR__.'/lang' => $this->app->langPath('vendor/theme-manager'),
        ], 'theme-manager-translations');

        // Routes admin
        Route::group([
            'middleware' => config('core-cms.admin.middleware'),
            'prefix' => config('core-cms.admin.prefix'),
            'as' => config('core-cms.admin.name'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
        });

        $menuManager->registerMenuItem('theme', [
            'label' => trans_choice('theme-manager::admin.theme.value', 0),
            'icon' => 'theme',
            'route' => 'admin.themes.index',
            'can' => 'option-list'
        ]);
    }
}
