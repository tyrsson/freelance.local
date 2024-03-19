<?php
return [
    'data' => [
        'contact' => [
            'elementMap' => [
                'enableMap' => 'CheckBox',
                'location'  => 'Text',
                'email'     => 'Text',
                'phone'     => 'Text',
                'contactBlurb' => 'TextArea',
            ],
            'enableMap'    => true, // bool true|false
            'location'     => null, // string|null
            'email'        => 'info@example.com', // string|null
            'phone'        => '+1 5589 55488 55s', // string|null
            'contactBlurb' => 'This is your contact blurb.', // string|null
        ],
    ],
];
