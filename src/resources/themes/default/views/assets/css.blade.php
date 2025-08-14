<?php
    $cacheBuster = $cacheBuster ?? substr(md5(json_encode(now())), 0, 8);
?>


<link rel="preload" href="{{ route('assets.show', ['path' => 'css/critical.css']) }}?v={{ $cacheBuster }}" as="style">
<link rel="preload" href="{{ route('assets.show', ['path' => 'css/app.css']) }}?v={{ $cacheBuster }}" as="style">

<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/critical.css']) }}?v={{ $cacheBuster }}">
<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/app.css']) }}?v={{ $cacheBuster }}">