<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class TimeoutException extends InvalidParamsException
{
    public const SUB_CODE = 'timeout';
    public const SUB_MSG = '请求超时';
}
