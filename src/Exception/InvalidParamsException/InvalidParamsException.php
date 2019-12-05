<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


use Zhan3333\ApiRsa\Exception\ApiRSAException;

class InvalidParamsException extends ApiRSAException
{
    public const CODE = parent::INVALID_PARAMS;
    public const MSG = '非法的参数';
}
