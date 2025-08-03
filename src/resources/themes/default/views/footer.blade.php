@extends('shared.blocks.layouts.layout')

@php
    $classes = ['padding-block-5'];
@endphp


@section('class')
    {{ join(' ', $classes) }}
@overwrite

@section('element')
    div
@overwrite

@section('content')
    <div class="container">
        <div class="even-columns margin-block-end-4">
            <div class="footer__info-item">
                <h2 class="fs-700 fw-blod margin-block-end-4">Sitemap</h2>
                @if(key_exists('links', $bloc))
                    <ul>
                        @foreach($bloc['links'] as $link)
                            @if($link['url'] !== '')
                                @php
                                    if($link['type'] == 'internal') {
                                        $json = json_decode($link['url'], true);
                                        $path = key_exists('slug', $json) ? route($json['path'], $json['slug']) : route($json['path']);
                                        $label = $link['label'] !== '' ? $link['label'] :  $json['label'];
                                    } else {
                                        $path = $link['url'];
                                        $label = $link['label'];
                                    }

                                @endphp
                                <li><a href="{{ $path }}" {{ menu_active($menu, $path) }}>{{ $label }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @if(key_exists('credit', $bloc))
            <h3 class="text-center fs-500 fw-bold margin-block-start-6">
                {!! $bloc['credit'] !!}
            </h3>
        @endif
    </div>
@overwrite
