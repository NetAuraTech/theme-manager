<?php

namespace Netauratech\ThemeManager\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Netauratech\CoreCms\Contracts\AssetSourceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThemeAssetSource implements AssetSourceInterface
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * Attempts to resolve an asset from the active theme's public folder.
     *
     * @param string $path The relative path of the asset.
     */
    public function resolve(string $path): Response|BinaryFileResponse|null
    {
        try {
            $basePath = $this->themeManager->getThemePath();
            $assetPath = "{$basePath}/{$path}";

            if (!File::exists($assetPath)) {
                abort(404, __('core-cms::core.asset.notfound'));
            }

            $extension = File::extension($assetPath);

            $mimeType = match ($extension) {
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'text/plain',
            };

            return FacadeResponse::make(File::get($assetPath), 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        } catch (\Exception $e) {
            Log::warning("ThemeAssetSource: Error resolving theme asset for path {$path} : " . $e->getMessage());
        }

        return null;
    }
}