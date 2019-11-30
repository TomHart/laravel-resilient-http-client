<?php
return [
    /*
     * How long should the resilient client hold on to it's cache for?
     */
    'cache_time' => env('RESILIENT_CACHE_TIME', 600),

    /*
     * Prefix the resilient cache key
     */
    'cache_prefix' => env('RESILIENT_CACHE_PREFIX', 'resilient')

];
