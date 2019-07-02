<?php

/*
 * Set specific configuration variables here
 */
return [
    // automatic loading of routes through main service provider
    'routes' => true,
    // layout where the Business views will show into, i.e. admin.layouts.master
    'layout-master' => 'business::admin.layout',
    'models' => [
        'business' => '\Sinevia\Business\Models\Business',
        'customer' => '\Sinevia\Business\Models\Customer',
        'invoice' => '\Sinevia\Business\Models\Invoice',
        'invoice_item' => '\Sinevia\Business\Models\InvoiceItem',
        'transaction' => '\Sinevia\Business\Models\Transaction',
    ],
    'urls' => [
        'media-manager' => '/your-media-manager-url',
    ]
];