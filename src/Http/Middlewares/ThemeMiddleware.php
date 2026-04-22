<?php

namespace Netauratech\ThemeManager\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Netauratech\CoreCms\Contracts\ThemeMiddlewareInterface;
use Netauratech\CoreCms\Services\AssetManager;
use Netauratech\ThemeManager\Services\ThemeManager;

class ThemeMiddleware implements ThemeMiddlewareInterface
{
    protected AssetManager $assetManager;
    protected ThemeManager $themeManager;

    public function __construct(AssetManager $assetManager, ThemeManager $themeManager)
    {
        $this->assetManager = $assetManager;
        $this->themeManager = $themeManager;
    }

    public function handle($request, Closure $next)
    {
        $hasOptionsTable = Cache::rememberForever('schema_has_options', function () {
            return Schema::hasTable('options');
        });

        if ($hasOptionsTable) {
            $themePath = $this->themeManager->getThemePath();

            app('view')->prependNamespace('theme', $themePath.'/views');

            $this->assetManager->registerTranslationPath('theme', $themePath.'/lang');
            app('translator')->addNamespace('theme', $themePath.'/lang');
        }

        return $next($request);
    }
}