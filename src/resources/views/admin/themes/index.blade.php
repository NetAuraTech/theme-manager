@extends('core-cms::admin.base')

@section('title')
    {{ __('core-cms::admin.manage') }} {{ trans_choice('theme-manager::admin.theme.value', 2) }}
@endsection

@section('body')
    <section class="grid">
        <div class="flex-group justify-content-space-between align-items-center" style="width: initial">
            <h2 class="heading-2 flex-group align-items-center">{!! icon('theme', 'small') !!} {{ __('core-cms::admin.manage') }} {{ trans_choice('theme-manager::admin.theme.value', 2) }}</h2>
        </div>
        <div class="card">
            <form
                class="grid"
                action="{{ route('admin.themes.upload') }}"
                method="post"
                enctype="multipart/form-data"
            >
                @csrf
                <input type="file" is="drop-files" label="{{ __('theme-manager::admin.theme.upload.info') }}"
                       help="{{ __('theme-manager::admin.theme.upload.help') }}" name="zip" accept=".zip">
                <div class="text-center">
                    <button type="submit" class="button" data-type="primary">{{ __('core-cms::admin.send') }}</button>
                </div>
            </form>
        </div>
        <h2 class="heading-2 flex-group align-items-center">{{ __('theme-manager::admin.theme.installed') }}</h2>
        <div class="card themes">
            <div class="grid-auto-fit align-items-center">
                @php
                    $themeManager = app(Netauratech\ThemeManager\Services\ThemeManager::class);
                    $themeAssetSource = app(Netauratech\ThemeManager\Services\ThemeAssetSource::class);

                    $themes = $themeManager->getAllThemes();
                @endphp
                @foreach($themes as $theme)
                    <div class="theme">
                        <img src="{{ route('assets.show', ['path' => 'preview.png', 'theme' => $theme]) }}" alt="Theme preview">
                        <footer class="flex-group justify-content-space-between align-items-center padding-2">
                            <h2>
                                {{ $theme }}
                                @if($options['theme'] === $theme)
                                    <span>({{ __('theme-manager::admin.theme.enabled') }})</span>
                                @endif
                            </h2>
                            <div class="flex-group align-items-center">
                                @if($options['theme'] !== $theme)
                                    <form
                                        class="clr-green-500"
                                        action="{{ route('admin.themes.define', $theme) }}"
                                        method="post">
                                        @csrf
                                        <button
                                            class="button padding-0"
                                            data-type="transparent"
                                            type="submit"
                                            title="{{ __('theme-manager::admin.theme.enable.value') }} {{ $theme }}"
                                        >
                                            {!! icon('confirm', 'small') !!}
                                        </button>
                                    </form>
                                @endif
                                @if($theme !== 'default')
                                    <form
                                        class="clr-red-300"
                                        action="{{ route('admin.themes.destroy', $theme) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button
                                            class="button padding-0"
                                            data-type="transparent"
                                            type="submit"
                                            title="{{ __('core-cms::admin.delete.value') }} {{ $theme }}"
                                        >
                                            {!! icon('trash', 'small') !!}
                                        </button>
                                    </form>
                                @endif
                                @if($theme !== 'default')
                                    <a
                                        href="{{ route('admin.themes.compile', $theme) }}"
                                        class="button padding-0"
                                        data-type="transparent"
                                        title="{{ __('theme-manager::admin.theme.build.value') }} {{ $theme }}"
                                    >
                                        {!! icon('compile', 'small') !!}
                                    </a>
                                @endif
                            </div>
                        </footer>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
