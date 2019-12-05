<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\BusinessException;


use Zhan3333\ApiRsa\Exception\ApiRSAException;

class BusinessException extends ApiRSAException
{
    public const CODE = parent::BUSINESS_ERROR;
    public const MSG = '业务处理失败';
    public const SUB_CODE = '';
    public const SUB_MSG = '';
}
