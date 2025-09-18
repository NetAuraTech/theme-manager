<?php

namespace Netauratech\ThemeManager;

use Illuminate\Contracts\Container\BindingResolutionException;
use Netauratech\CoreCms\Events\LangLoaded;
use Netauratech\CoreCms\Events\OptionUpdated;
use Netauratech\CoreCms\Services\AbstractCmsServiceProvider;
use Netauratech\CoreCms\Services\Admin\MenuManager;
use Netauratech\CoreCms\Services\AssetManager;
use Netauratech\ThemeManager\Listeners\ClearThemeCache;
use Netauratech\ThemeManager\Services\ThemeAssetSource;
use Netauratech\ThemeManager\Services\ThemeManager;

class ThemeManagerServiceProvider extends AbstractCmsServiceProvider
{
    protected function getPackageName(): string
    {
        return 'theme-manager';
    }

    protected function getBootstrapConfig(): array
    {
        $config = parent::getBootstrapConfig();

        $config['views'] = false;
        $config['assets'] = false;
        $config['migrations'] = false;
        $config['seeders'] = false;
        $config['routes']['web'] = false;
        $config['routes']['api'] = false;
        $config['routes']['auth'] = false;
        $config['publishes']['migrations'] = false;
        $config['publishes']['seeders'] = false;
        $config['publishes']['config'] = false;
        $config['publishes']['assets'] = false;

        return $config;
    }

    public function register(): void
    {
        $this->app->singleton(ThemeManager::class);
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

        $this->loadViewsFrom(__DIR__.'/resources/views', 'theme');
        $this->loadViewsFrom($themePath.'/views', 'theme');

        $assetManager->registerTranslationPath('theme', $themePath.'/lang');
        $this->loadTranslationsFrom($themePath.'/lang', 'theme');

        $this->bootstrapPackage();

        $menuManager->registerMenuItem('theme', [
            'label' => trans_choice('theme-manager::admin.theme.value', 0),
            'icon' => 'theme',
            'route' => 'admin.themes.index',
            'can' => 'option-list'
        ]);
    }
}
