<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Conversions;

use Illuminate\Http\Request;

class ConversionRequest
{
    /**
     * 转换业务参数到实际参数
     * - 处理数据示例
     *     - app_id
     *     - method
     *     - format
     *     - charset
     *     - sign_type
     *     - sign
     *     - timestamp
     *     - version
     *     - notify_url
     *     - app_auth_token
     *     - biz_content
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
     */
    public static function handle(\Illuminate\Http\Request $request)
    {
        $request->replace(json_decode($request->input('biz_content'), true) ?? []);
        return $request;
    }
}
