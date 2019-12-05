<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class InvalidTimestampException extends InvalidParamsException
{
    public const SUB_CODE = 'invalid-timestamp';
    public const SUB_MSG = '无效时间戳参数';
}
