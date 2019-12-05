<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidSignTypeException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-sign-type';
    public const SUB_MSG = '无效签名类型';
}
