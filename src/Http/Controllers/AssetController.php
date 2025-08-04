<?php

namespace Netauratech\ThemeManager\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Netauratech\ThemeManager\Services\ThemeManager;

class AssetController extends Controller
{
    public function show($path, ThemeManager $themeManager): Response
    {
        $basePath = $themeManager->getThemePath();
        $assetPath = "{$basePath}/{$path}";

        if (!File::exists($assetPath)) {
            abort(404, __('cms.asset.notfound'));
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
    }
}