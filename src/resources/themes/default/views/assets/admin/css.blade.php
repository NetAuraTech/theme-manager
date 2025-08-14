<?php
    $cacheBuster = $cacheBuster ?? substr(md5(json_encode(now())), 0, 8);
?>

<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/admin.css']) }}?v={{ $cacheBuster }}">
