<?php

namespace Netauratech\ThemeManager\Http\Controllers\Admin;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Netauratech\CoreCms\Http\Controllers\AdminController;
use Netauratech\CoreCms\Models\Option;
use Netauratech\CoreCms\Services\CacheService;
use Netauratech\ThemeManager\Jobs\CompileTheme;
use Netauratech\ThemeManager\Jobs\MinifyTheme;
use Netauratech\ThemeManager\Services\ThemeManager;
use ZipArchive;

class ThemeController extends AdminController
{
    private ThemeManager $themeManager;
    public function __construct(ThemeManager $themeManager)
    {
        parent::__construct();
        $this->themeManager = $themeManager;
    }

    /**
     * Displays the list of available themes in the admin panel.
     *
     * @return View
     */
    public function index(): View
    {
        return view('theme::admin.themes.index', [
        ]);
    }

    /**
     * Uploads and extracts a theme archive (ZIP) into the storage path.
     * Only allowed file types are extracted (images, CSS, SCSS, PHP, JS, fonts).
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception If the ZIP file cannot be opened.
     */
    public function upload(Request $request): RedirectResponse
    {
        $zip = new ZipArchive();
        $status = $zip->open($request->file('zip')->getRealPath());
        if ($status !== true) {
            throw new Exception($status);
        } else {
            $storageDestinationPath = storage_path('app/private/themes');
            if (!File::exists($storageDestinationPath)) {
                File::makeDirectory($storageDestinationPath, 0755, true);
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $OnlyFileName = str_replace('../', '', $zip->getNameIndex($i));
                $FullFileName = $zip->statIndex($i);
                if (!($FullFileName['name'][strlen($FullFileName['name']) - 1] == '/')) {
                    if (preg_match('#\.(jpg|jpeg|gif|png|svg|lottie|css|scss|php|js|woff|ttf)$#i', $OnlyFileName)) {
                        $dirname = pathinfo($FullFileName['name'], PATHINFO_DIRNAME);
                        $basename = pathinfo($FullFileName['name'], PATHINFO_BASENAME);
                        $name = $dirname . '/' . $basename;
                        $file = $zip->getFromIndex($i);
                        Storage::disk('local')->put('/themes/' . $name, $file);
                    }
                }
            }

            $zip->close();

            return to_route('admin.themes.index')->with('success', __('theme-manager::admin.theme.upload.confirm'));
        }
    }

    /**
     * Sets a given theme as the active theme and clears the cache.
     *
     * @param string $theme
     * @param CacheService $cache
     * @return RedirectResponse
     */
    public function define(string $theme, CacheService $cache): RedirectResponse
    {
        Option::where('key', 'theme')->update(['value' => $theme]);

        $cache->clear();

        return to_route('admin.themes.index')->with('success', __('theme-manager::admin.theme.enable.confirm'));
    }

    /**
     * Compiles and minifies the specified theme.
     *
     * @param string $theme
     * @param CacheService $cache
     * @return RedirectResponse
     */
    public function compile(string $theme, CacheService $cache): RedirectResponse
    {
        $path = $this->themeManager->getThemePath($theme);
        CompileTheme::dispatch($path);
        MinifyTheme::dispatch($path);

        $cache->clear();

        return to_route('admin.themes.index')->with('success', __('theme-manager::admin.theme.build.confirmed'));
    }

    /**
     * Deletes a theme from storage. If the theme is active, it will revert to the default theme.
     * The default theme cannot be deleted.
     *
     * @param string $theme
     * @return RedirectResponse
     */
    public function destroy(string $theme): RedirectResponse
    {
        if($theme !== 'default') {
            $option = Option::where('key', 'theme')->first();

            if ($option->value === $theme) {
                Option::where('key', 'theme')->update(['value' => 'default']);
            }

            $path = $this->themeManager->getThemePath($theme);

            File::deleteDirectory($path);
            return to_route('admin.themes.index')->with('success',  __('theme-manager::admin.theme.delete.confirm'));
        }


        return to_route('admin.themes.index')->with('error',  __('theme-manager::admin.theme.delete.error'));
    }
}
