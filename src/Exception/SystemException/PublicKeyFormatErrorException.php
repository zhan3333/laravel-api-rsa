<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\SystemException;


use Zhan3333\ApiRsa\Exception\ApiRSAException;

class PublicKeyFormatErrorException extends ApiRSAException
{
    public const SUB_CODE = 'system-error-public-key-format-error';
    public const SUB_MSG = '公钥格式错误';
}
