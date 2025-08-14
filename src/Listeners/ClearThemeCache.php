<?php

namespace Netauratech\ThemeManager\Listeners;

use Netauratech\CoreCms\Events\OptionUpdated;
use Netauratech\ThemeManager\Services\ThemeManager;

class ClearThemeCache
{
    /**
     * @var ThemeManager
     */
    protected ThemeManager $themeManager;

    /**
     * Creates the event listener.
     *
     * @param ThemeManager $themeManager
     */
    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * Handles the OptionUpdated event.
     *
     * @param OptionUpdated $event
     * @return void
     */
    public function handle(OptionUpdated $event): void
    {
        if ($event->option->key === 'theme') {
            $this->themeManager->clearCache();
        }
    }
}