<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;


class MissTimestampException extends MissingParamsException
{
    public const SUB_CODE = 'missing-timestamp';
    public const SUB_MSG = '缺少时间戳参数';
}
