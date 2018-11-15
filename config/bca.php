<?php

/**
 * Laravel BCA REST API Config.
 *
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2017, Pribumi Technology
 */
return [

    'default'     => 'main',

    'connections' => [

        'main'        => [
            'corp_id'       => 'BCAAPI2016',
            'client_id'     => 'OTNhMjUyYzYtYmQxZS00OGZiLTg1YzgtNTMxOWY5NTczNzZh',
            'client_secret' => 'NjM4OWM1OTAtNmJkYi00ODFhLThmNWQtNWJiNGE1ZGEyNTU1',
            'api_key'       => 'NzExMmNjZDItZmNjNi00ZjRlLTllNzQtNWZmNWEzMjEwNDQ1',
            'secret_key'    => 'MjI1YTUzZTUtNDY2Zi00YTIwLWJhZmYtY2NjMGIzMzdlOTVm',
            'timezone'      => 'Asia/Jakarta',
            'host'          => 'sandbox.bca.co.id',
            'scheme'        => 'https',
            'development'   => true,
            'options'       => [],
            'port'          => 443,
            'timeout'       => 30,
        ],

        'alternative' => [
            'corp_id'       => 'your-corp_id',
            'client_id'     => 'your-client_id',
            'client_secret' => 'your-client_secret',
            'api_key'       => 'your-api_key',
            'secret_key'    => 'your-secret_key',
            'timezone'      => 'Asia/Jakarta',
            'host'          => 'sandbox.bca.co.id',
            'scheme'        => 'https',
            'development'   => true,
            'options'       => [],
            'port'          => 443,
            'timeout'       => 30,
        ],

    ],

];
