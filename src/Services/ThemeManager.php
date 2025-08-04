<?php

namespace Netauratech\ThemeManager\Services;

class ThemeManager
{
    protected $activeTheme;

    public function __construct(string $activeTheme)
    {
        $this->activeTheme = $activeTheme;
    }

    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    /**
     * Determines whether the theme is a package theme or an uploaded theme.
     */
    public function isUploadedTheme(): bool
    {
        //TODO: Fetch database to retrive current theme.
        return $this->activeTheme !== 'default';
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
}