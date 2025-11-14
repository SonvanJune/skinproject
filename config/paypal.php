<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'         => getPaypalInformation('client_id'),
        'client_secret'     => getPaypalInformation('client_secret'),
        'app_id'            => getPaypalInformation('app_id'),
    ],
    'live' => [
        'client_id'         => getPaypalInformation('client_id'),
        'client_secret'     => getPaypalInformation('client_secret'),
        'app_id'            => getPaypalInformation('app_id'),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''),
    'locale'         => env('PAYPAL_LOCALE', 'en_US'),
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', ''), 
];
