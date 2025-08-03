<?php

namespace NetAuraTech\ThemeManager\Services;

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
     * Détermine si le thème est un thème du package ou un thème uploadé.
     */
    public function isUploadedTheme(): bool
    {
        // Pour cet exemple, on suppose que le thème "default-admin"
        // est le seul thème qui fait partie du package.
        return $this->activeTheme !== 'default';
    }

    /**
     * Retourne le chemin complet vers le dossier du thème actif.
     */
    public function getThemePath(): string
    {
        // Si c'est un thème uploadé, on utilise le chemin de stockage.
        if ($this->isUploadedTheme()) {
            return storage_path('app/themes/'.$this->getActiveTheme());
        }

        // Sinon, on retourne le chemin des ressources du package.
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, dirname(__DIR__) . '/resources/themes/' . $this->getActiveTheme());
    }
}