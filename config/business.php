<?php

/*
 * Set specific configuration variables here
 */
return [
    // automatic loading of routes through main service provider
    'routes' => true,
    // layout where the Business views will show into, i.e. admin.layouts.master
    'layout-master' => 'business::admin.layout',
    'urls' => [
        'media-manager' => '/your-media-manager-url',
    ]
];