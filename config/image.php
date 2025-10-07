<?php

return [
    'fallbacks' => [
        env('IMG_FALLBACK_1', 'https://google.com/{basename}'), 
        env('IMG_FALLBACK_2', 'https://facebook.com/{basename}'), 
    ],
    'placeholder' => env('IMG_PLACEHOLDER', '/assets/img/no-image.png'),

    'timeout'   => (float) env('IMG_CHECK_TIMEOUT', 2.0),  // วินาที
    'cache_ttl' => (int)   env('IMG_CHECK_CACHE_TTL', 600), // วินาที
];