<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception;


use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ApiRSAException extends \Exception
{
    public const CODE = '';
    public const MSG = '';
    public const SUB_CODE = '';
    public const SUB_MSG = '';

    // 服务器错误
    public const UN_KNOW_ERROR = '20000';
    // 缺少参数
    public const MISSING_PARAMS = '40001';
    // 无效参数
    public const INVALID_PARAMS = '40002';
    // 业务处理失败
    public const BUSINESS_ERROR = '40004';

    public function response(): Response
    {
        return Response::create([
            'msg' => static::MSG,
            'code' => static::CODE,
            'sub_msg' => empty($this->message) ? $this::SUB_MSG : $this->message,
            'sub_code' => static::SUB_CODE,
            'nonce' => Str::random(32),
            'biz_content' => null,
        ]);
    }
}
