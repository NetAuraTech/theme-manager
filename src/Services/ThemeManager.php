<?php

namespace Netauratech\ThemeManager\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Netauratech\CoreCms\Models\Option;
use Illuminate\Support\Facades\File;

class ThemeManager
{
    protected string $defaultTheme = 'default';

    /**
     * Retrieves the name of the active theme from the CMS options.
     * The result is cached to improve performance
     *
     * @param string|null $theme
     * @return string
     */
    public function getActiveTheme(?string $theme = null): string
    {
        if($theme) {
            return $theme;
        }

        $cache = Cache::getFacadeRoot();
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
     * @param string|null $theme
     * @return bool
     */
    public function isUploadedTheme(?string $theme = null): bool
    {
        return $this->getActiveTheme($theme) !== $this->defaultTheme;
    }

    /**
     * Returns the full path to the active theme folder.
     * @param string|null $theme
     * @return string
     */
    public function getThemePath(?string $theme = null): string
    {
        // If it is an uploaded theme, the storage path is used.
        if ($this->isUploadedTheme($theme)) {
            return storage_path('app/private/themes/'.$this->getActiveTheme($theme));
        }

        // Otherwise, we return the package resource path.
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, dirname(__DIR__) . '/resources/themes/' . $this->getActiveTheme($theme));
    }

    /**
     * Retrieves all available themes from both default and uploaded theme directories.
     *
     * This method scans:
     * - The default themes directory located in the package's `resources/themes`.
     * - The uploaded themes directory located in `storage/app/private/themes`.
     *
     * @return string[] Array of theme directory names.
     */
    public function getAllThemes(): array
    {
        $themes = [];

        $defaultThemesPath = dirname(__DIR__) . '/resources/themes';
        if (File::exists($defaultThemesPath)) {
            foreach (File::directories($defaultThemesPath) as $dir) {
                $themes[] = basename($dir);
            }
        }

        $uploadedThemesPath = storage_path('app/private/themes');

        if (File::exists($uploadedThemesPath)) {
            foreach (File::directories($uploadedThemesPath) as $dir) {
                $themes[] = basename($dir);
            }
        }

        return $themes;
    }

    /**
     * Clears the cache for the active theme.
     * This method will be called when the theme is changed via the options.
     *
     * @return void
     */
    public function clearCache(): void
    {
        $cache = Cache::store('database');
        $cache->forget('theme_manager_active_theme_name');
    }
}