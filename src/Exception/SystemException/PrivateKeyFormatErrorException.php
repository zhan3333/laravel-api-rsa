<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\SystemException;


use Zhan3333\ApiRsa\Exception\ApiRSAException;

class PrivateKeyFormatErrorException extends ApiRSAException
{
    public const SUB_CODE = 'system-error-private-key-format-error';
    public const SUB_MSG = '私钥格式错误';
}
