<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;


use Throwable;

class MissAPPIDException extends MissingParamsException
{
    public const SUB_CODE = 'missing-app-id';
    public const SUB_MSG = '缺少 app_id 参数';
}
