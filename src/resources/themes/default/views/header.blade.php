@extends('shared.blocks.layouts.layout')

@php
    $classes = [];
@endphp


@section('class')
    {{ join(' ', $classes) }}
@overwrite

@section('element')
    header
@overwrite

@section('content')
    <a href="{{ route('home') }}" class="site-header__logo fs-600">
        {{ $options['sitename'] }}
    </a>
    <ul class="nav fs-600">
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
    <button
        id="js-burger"
        class="site-header__burger"
        aria-controls="primary-navigation"
        aria-expanded="false"
        data-state="closed"
    >
        <svg
            stroke="currentColor"
            fill="none"
            class="hamburger"
            viewBox="-10 -10 120 120"
            width="50"
        >
            <path
                class="line"
                stroke-width="6"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="m 20 40 h 60 a 1 1 0 0 1 0 20 h -60 a 1 1 0 0 1 0 -40 h 30 v 70"
            ></path>
        </svg>
    </button>
@overwrite
