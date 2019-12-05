<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\InvalidParamsException;



class DuplicateRequestException extends InvalidParamsException
{
    public const SUB_CODE = 'duplicate-request';
    public const SUB_MSG = '重复的请求';
}
