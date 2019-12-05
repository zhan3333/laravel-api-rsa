<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidFormatException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-format';
    public const SUB_MSG = '无效的数据格式';
}
