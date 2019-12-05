<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidVersionException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-version';
    public const SUB_MSG = '无效版本';
}
