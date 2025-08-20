<?php

use Modules\Twp\Http\Middleware\HandleInertiaRequests;

return [
    'name' => 'Twp',
    'middleware' => [
        HandleInertiaRequests::class,
    ],
];
