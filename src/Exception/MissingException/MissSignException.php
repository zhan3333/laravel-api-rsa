<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;



class MissSignException extends MissingParamsException
{
    public const SUB_CODE = 'missing-sign';
    public const SUB_MSG = '缺少签名参数';
}
