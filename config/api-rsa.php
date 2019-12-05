<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

return [
    'request' => [
        // 请求超时时间
        'timeout' => 15,
        'tags' => [
            'api-rsa', 'request', 'sign'
        ],
        'duplicate_request_cache' => 15,
        'allow_version' => ['1.0'],
        'allow_sign_type' => ['RSA2'],
        'allow_charset' => ['UTF-8'],
        'allow_format' => ['JSON'],
    ],
    'response' => [
        'response_name' => 'esign_response',
    ],
    'log' => [
        'enable' => true,
        'channel' => 'single'
    ],
    'models' => [
        // 服务端才需要配置该类
        'api_rsa' => \Zhan3333\ApiRsa\Models\ApiRSAKey::class,
    ],
];
