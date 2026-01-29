<?php

return [
    /*
     * The key used to sign media URLs.
     */
    'signing_key' => env('MEDIA_SIGNING_KEY'),

    /*
     * The default number of days a signed URL is valid.
     */
    'ttl_days' => env('MEDIA_TTL_DAYS', 14),
];
