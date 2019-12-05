<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Exception\BusinessException;


use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ValidationException extends BusinessException
{
    public const SUB_CODE = 'business-params-error';
    public const SUB_MSG = '业务参数错误';

    public function response(): Response
    {
        return Response::create([
            'msg' => self::MSG,
            'code' => self::CODE,
            'sub_msg' => $this->message,
            'sub_code' => self::SUB_CODE,
            'nonce' => Str::random(32),
            'biz_content' => null,
        ]);
    }
}
