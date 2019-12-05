<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;


class APPIDNotRegisteredException extends InvalidParamsException
{
    public const SUB_CODE = 'app-id-not-registered';
    public const SUB_MSG = 'app_id 未注册';
}
