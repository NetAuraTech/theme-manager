@extends('shared.blocks.layouts.layout')

@php
    $bloc = $bloc ??  [];
    $useContainer = $useContainer ?? $bloc['use-container'] ?? true;
    $section = $section ?? 'section';

    $classes = [''];
    $animations = [];
    $animate = $animate ?? true;
    if($animate) {
        if(key_exists('animation', $bloc) && $bloc['animation'] !== "") {
            $animations[] = 'animate';
            $animations[] = $bloc['animation'];
        }
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
            @php
                $emptyPositions = [3, 5, 7, 8];
                $collageItems = [];
                $imageIndex = 0;
                $totalItems = count($bloc['images']) + count($emptyPositions);

                for ($i = 0; $i < 12; $i++) {
                    if (in_array($i, $emptyPositions)) {
                        $collageItems[] = "<div aria-hidden=\"true\"></div>";
                    } elseif (isset($bloc['images'][$imageIndex])) {
                        $url = image_url($bloc['images'][$imageIndex]['image']);
                        $collageItems[] = "<a href=\"$url\">" . imageTag($bloc['images'][$imageIndex]['image'], key_exists('image-alt', $bloc['images'][$imageIndex]) ? $bloc['images'][$imageIndex]['image-alt'] : null, 482) ."</a>";
                        $imageIndex++;
                    } else {
                        $collageItems[] = "<div aria-hidden=\"true\"></div>";
                    }
                }
            @endphp
            <light-box>
                <div class="grid-collage">
                    @foreach ($collageItems as $item)
                        {!! $item !!}
                    @endforeach
                </div>
            </light-box>
            @if($useContainer)</div>
    @endif
@overwrite
