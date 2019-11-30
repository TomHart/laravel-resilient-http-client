<?php
return [
    /*
     * How long should the normal client hold on to it's cache for? Time in seconds
     */
    'cache_time' => env('RESILIENT_CACHE_TIME', 60),

    /*
     * How long should the fallback client hold on to it's cache for? Time in seconds
     */
    'fallback_cache_time' => env('RESILIENT_FALLBACK_CACHE_TIME', 3600),
];
