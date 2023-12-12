<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;


use Illuminate\Http\Request;

class PaypalController extends Controller
{
    //

    
    $provider = new PayPalClient;

    // Through facade. No need to import namespaces
    $provider = \PayPal::setProvider();

    $config = [
        'mode'    => 'live',
        'live' => [
            'client_id'         => 'AWardK88AMJBiTb_5RqvLVgguHp9Ur-prq8F8mz2suHS1IXzbfT8fveUSS8D2tXb_8cmj9_D-N017t5w',
            'client_secret'     => 'EKZs2ejThbd_-hHX793JbkseUHiL6uv4_ikaDWa1p8e29QkfZAL5raZ1fvMpGYP1_Zc5rxeHGlzSC7Hc',
            // 'app_id'            => 'APP-80W284485P519543T',
        ],
    
        'payment_action' => 'Sale',
        'currency'       => 'USD',
        'notify_url'     => 'https://your-app.com/paypal/notify',
        'locale'         => 'en_US',
        'validate_ssl'   => true,
    ];
    
    $provider->setApiCredentials($config);
}
