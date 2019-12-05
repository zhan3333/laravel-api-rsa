<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;



use Zhan3333\ApiRsa\Exception\ApiRSAException;

class MissingParamsException extends ApiRSAException
{
    public const CODE = parent::MISSING_PARAMS;
    public const MSG = '缺少必选参数';
}
