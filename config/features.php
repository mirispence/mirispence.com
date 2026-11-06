<?php

/*
|--------------------------------------------------------------------------
| Feature flags (ADR-0006)
|--------------------------------------------------------------------------
|
| Each flag switches a whole area of the site on or off. Pennant resolves
| every flag from this file (see AppServiceProvider) using its `array`
| store, so changing the env var takes effect on the next request, with no
| stored state to purge. Flags are global: everyone sees the same value.
|
*/

return [

    // Books and chapters, on the public site and in the admin.
    'books' => (bool) env('FEATURE_BOOKS', false),

];
