<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC Scheme
    |--------------------------------------------------------------------------
    | URI scheme of Paychain Core's JSON-RPC server.
    |
    | Use https to enable SSL connections with Core,
    | but this is strongly discouraged by developers.
    |
    */

    'scheme' => env('PAYCHAIN_SCHEME', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC Host
    |--------------------------------------------------------------------------
    | Tells service provider which hostname or IP address
    | Paychain Core is running at.
    |
    | If Paychain Core is running on the same PC as
    | laravel project use localhost or 127.0.0.1.
    |
    | If you're running Paychain Core on the different PC,
    | you may also need to add rpcallowip=<server-ip-here> to your bitcoin.conf
    | file to allow connections from your laravel client.
    |
    */

    'host' => env('PAYCHAIN_HOST', 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC Port
    |--------------------------------------------------------------------------
    | The port at which Paychain Core is listening for JSON-RPC connections.
    | Default is 8554 for mainnet and 18554 for testnet.
    | You can also directly specify port by adding rpcport=<port>
    | to paychain.conf file.
    |
    */

    'port' => env('PAYCHAIN_PORT', 8554),

    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC User
    |--------------------------------------------------------------------------
    | Username needs to be set exactly as in bitcoin.conf file
    | rpcuser=<username>.
    |
    */

    'user' => env('PAYCHAIN_USER', ''),

    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC Password
    |--------------------------------------------------------------------------
    | Password needs to be set exactly as in bitcoin.conf file
    | rpcpassword=<password>.
    |
    */

    'password' => env('PAYCHAIN_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | Paychain JSON-RPC Server CA
    |--------------------------------------------------------------------------
    | If you're using SSL (https) to connect to your Paychain Core
    | you can specify custom ca package to verify against.
    | Note that using Paychain JSON-RPC over SSL is strongly discouraged.
    |
    */

    'ca' => null,

     /*
    |--------------------------------------------------------------------------
    | LaravelPaychain  - this param is for minimum number of confirmations
    |--------------------------------------------------------------------------
    | This is minimal number of confirmations that we need
    | to consider payment is safe.
    |
    */

    'min-confirmations' => env('PAYCHAIN_MIN_CONFIRMATIONS', 3),
    'order-model'       => env('PAYCHAIN_ORDER_MODEL', 'App\Order'),
    'user-model'        => env('PAYCHAIN_USER_MODEL', 'App\User'),

];
