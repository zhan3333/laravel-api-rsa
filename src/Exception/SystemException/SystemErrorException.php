<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\SystemException;


use Illuminate\Http\Response;
use Zhan3333\ApiRsa\Exception\ApiRSAException;

class SystemErrorException extends ApiRSAException
{
    public const CODE = parent::UN_KNOW_ERROR;
    public const MSG = '服务器错误';
    public const SUB_CODE = 'system-error';
    public const SUB_MSG = '服务器错误';
}
