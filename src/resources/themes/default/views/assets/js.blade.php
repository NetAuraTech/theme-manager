<?php
    $cacheBuster = $cacheBuster ?? substr(md5(json_encode(now())), 0, 8);
?>


<script src="{{ route('assets.show', ['path' => 'js/app.js']) }}?v={{ $cacheBuster }}" type="module" defer=""></script>