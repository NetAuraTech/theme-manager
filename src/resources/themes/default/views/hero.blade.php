@extends('shared.blocks.layouts.layout')

@php
    $bloc = $bloc ??  [];
    $useContainer = $useContainer ?? $bloc['use-container'] ?? true;
    $section = $section ?? 'section';

    $classes = ['hero'];
    $animations = [];

    $subTitleStyle = [];
    if(key_exists('sub-title-color', $bloc) && $bloc['sub-title-color'] !== "transparent") {
        $subTitleStyle[] = 'color: ' . $bloc['sub-title-color'] . ';';
    }
@endphp


@section('class')
    {{ join(' ', $classes) }}
@overwrite

@section('element')
    {{$section}}
@overwrite

@section('content')
    @if($useContainer)
        <div class="container {{ join(" ", $animations) }}"> @endif
            @if(key_exists('title', $bloc))
                <h1 class="heading-1 margin-block-end-6"
                    >{{ $bloc['title'] }}</h1>
            @endif
            <div style="display: inline-flex">
                @if(key_exists('sub-title', $bloc))
                    <h2 class="heading-2 margin-block-end-4"
                        @if(count($subTitleStyle) > 0)style="{{ implode(";", $subTitleStyle) }}"@endif>{{ $bloc['sub-title'] }}</h2>
                @endif
            </div>
            <div class="margin-block-end-4 clr-neutral-100">{!! $bloc['content'] !!}</div>
            @if(key_exists('ctas', $bloc))
                <div class="flex-group align-items-center">
                    @foreach($bloc['ctas'] as $cta)
                        @include('core-cms::shared.blocks.components.cta', ['bloc' => $cta])
                    @endforeach
                </div>
            @endif

            @if($useContainer)</div>
    @endif
@overwrite
