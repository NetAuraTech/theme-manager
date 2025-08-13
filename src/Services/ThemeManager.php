<?php

namespace Netauratech\ThemeManager\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Netauratech\CoreCms\Models\Option;

class ThemeManager
{
    protected string $defaultTheme = 'default';


    /**
     * Retrieves the name of the active theme from the CMS options.
     * The result is cached to improve performance
     *
     * @return string
     */
    public function getActiveTheme(): string
    {
        $cache = Cache::store('database');
        return $cache->rememberForever('theme_manager_active_theme_name', function () {
            if (!Schema::hasTable('options')) {
                return $this->defaultTheme;
            }

            $themeOption = Option::where('key', 'theme')->first();

            return $themeOption->value ?? $this->defaultTheme;
        });
    }

    /**
     * Determines whether the theme is a package theme or an uploaded theme.
     */
    public function isUploadedTheme(): bool
    {
        return $this->getActiveTheme() !== $this->defaultTheme;
    }

    /**
     * Returns the full path to the active theme folder.
     */
    public function getThemePath(): string
    {
        // If it is an uploaded theme, the storage path is used.
        if ($this->isUploadedTheme()) {
            return storage_path('app/themes/'.$this->getActiveTheme());
        }

        // Otherwise, we return the package resource path.
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, dirname(__DIR__) . '/resources/themes/' . $this->getActiveTheme());
    }

    /**
     * Clears the cache for the active theme.
     * This method will be called when the theme is changed via the options.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('theme_manager_active_theme_name');
    }
}