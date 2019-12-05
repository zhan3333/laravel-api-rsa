<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;


class MissFormatException extends MissingParamsException
{
    public const SUB_CODE = 'missing-format';
    public const SUB_MSG = '缺少数据格式参数';
}
