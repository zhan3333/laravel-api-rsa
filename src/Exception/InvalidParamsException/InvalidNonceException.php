<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidNonceException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-nonce';
    public const SUB_MSG = '随机字符串错误';
}
