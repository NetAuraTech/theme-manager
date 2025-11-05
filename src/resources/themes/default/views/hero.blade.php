@extends('core-cms::shared.blocks.layouts.layout')

@php
    $block = $block ??  [];
    $useContainer = $useContainer ?? $block['use-container'] ?? true;
    $section = $section ?? 'section';

    $classes = ['hero'];
    $animations = [];

    $subTitleStyle = [];
    if(key_exists('sub-title-color', $block) && $block['sub-title-color'] !== "transparent") {
        $subTitleStyle[] = 'color: ' . $block['sub-title-color'] . ';';
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
            @if(key_exists('title', $block))
                <h1 class="heading-1 margin-block-end-6"
                    >{{ $block['title'] }}</h1>
            @endif
            <div style="display: inline-flex">
                @if(key_exists('sub-title', $block))
                    <h2 class="heading-2 margin-block-end-4"
                        @if(count($subTitleStyle) > 0)style="{{ implode(";", $subTitleStyle) }}"@endif>{{ $block['sub-title'] }}</h2>
                @endif
            </div>
            <div class="margin-block-end-4 clr-neutral-100">{!! $block['content'] !!}</div>
            @if(key_exists('ctas', $block))
                <div class="flex-group align-items-center">
                    @foreach($block['ctas'] as $cta)
                        @includeIf('core-cms::shared.blocks.components.cta', ['block' => $cta])
                    @endforeach
                </div>
            @endif

            @if($useContainer)</div>
    @endif
@overwrite
