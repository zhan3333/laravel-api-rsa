<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa;


class ApiRSALog
{
    public function __call($name, $arguments)
    {
        if (config('api-rsa.log.enable')) {
            \Log::channel(config('api-rsa.log.channel'))->{$name}(...$arguments);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (config('api-rsa.log.enable')) {
            \Log::channel(config('api-rsa.log.channel'))->{$name}(...$arguments);
        }
    }
}
