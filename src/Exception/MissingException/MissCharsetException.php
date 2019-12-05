<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;


class MissCharsetException extends MissingParamsException
{
    public const SUB_CODE = 'missing-charset';
    public const SUB_MSG = '缺少字符集参数';
}
