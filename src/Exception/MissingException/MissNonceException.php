<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\MissingException;


/**
 * 缺少随机字符串参数
 * Class MissNonceException
 * @package Zhan3333\ApiRsa\Exception\MissingException
 */
class MissNonceException extends MissingParamsException
{
    public const SUB_CODE = 'missing-nonce';
    public const SUB_MSG = '缺少随机字符串参数';
}
