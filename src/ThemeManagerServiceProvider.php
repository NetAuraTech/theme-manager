<?php

namespace NetAuraTech\ThemeManager;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use NetAuraTech\ThemeManager\Services\ThemeManager;

class ThemeManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // On lie le service ThemeManager au conteneur.
        // On lui donne le nom du thème par défaut pour l'instant.
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