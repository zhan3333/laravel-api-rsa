<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Conversions;


use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ConversionResponse
{
    public const MSG = 'Success';
    public const CODE = '10000';
    public const SUB_MSG = '';
    public const SUB_CODE = '';

    /**
     * Convert base response to sign response
     *
     * - example
     *        - code
     *        - msg
     *        - sub_msg
     *        - sub_code
     *        - biz_content
     *
     * @param Response $response
     * @return \Illuminate\Http\Response
     */
    public static function handle(BaseResponse $response): BaseResponse
    {
        $content = $response->getContent();
        return response()->json([
            'msg' => self::MSG,
            'code' => self::CODE,
            'sub_msg' => self::SUB_MSG,
            'sub_code' => self::SUB_CODE,
            'nonce' => Str::random(32),
            'biz_content' => $content,
        ]);
    }
}
