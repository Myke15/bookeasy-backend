<?php

return [
    'services' => [
        'haircut'   => 'Haircut',
        'skin_care' => 'Skin Care',
        'facial'    => 'Facial',
        'scrub'     => 'Scrub',
    ],
    'max_booking_days_in_future' => env('MAX_BOOKING_DAYS_IN_FUTURE', 30),
];