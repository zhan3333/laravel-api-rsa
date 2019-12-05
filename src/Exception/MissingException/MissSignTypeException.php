<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;



class MissSignTypeException extends MissingParamsException
{
    public const SUB_CODE = 'missing-sign-type';
    public const SUB_MSG = '缺少签名类型参数';
}
