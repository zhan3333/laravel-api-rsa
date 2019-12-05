<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;



class MissVersionException extends MissingParamsException
{
    public const SUB_CODE = 'missing-version';
    public const SUB_MSG = '缺少版本参数';
}
