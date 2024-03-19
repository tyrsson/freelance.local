<?php
return [
    'settings' => [
        'siteName' => 'Axleus Technologies',
        'singlePage'         => true,
        'enableDropDownMenu' => false,
        'enableLogin'        => true,
        'showInMenu' => [ // todo: build menu from this
            'about', 'services', 'faq', 'skills', 'contact', 'login'
        ],
        'showOnHome' => [ // show pages from this on home page
            'about', 'services', 'faq', 'skills', 'contact',
        ],
    ],
];