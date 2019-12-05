<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidCharsetException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-charset';
    public const SUB_MSG = '字符集错误';
}
