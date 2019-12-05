<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * 用户端签名解签
 * Class ClientRSA
 * @package Zhan3333\ApiRsa
 */
class ClientRSA
{
    /**
     * RSA签名
     * @param string $private_key 私钥字符串
     * return 签名结果
     * @return string
     * @throws Exception
     */
    public function sign(array $data, $private_key): string
    {
        $sign = '';
        $all = array_except($data, ['sign', 'sign_type']);
        ksort($all);
        $buildStrArr = [];
        foreach ($all as $k => $v) {
            $buildStrArr[] = "$k=$v";
        }
        $buildStr = implode('&', $buildStrArr);
        $search = [
            '-----BEGIN PRIVATE KEY-----',
            '-----END PRIVATE KEY-----',
            "\n",
            "\r",
            "\r\n"
        ];

        $private_key = str_replace($search, '', $private_key);
        $private_key = $search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_privatekey($private_key);

        if ($res) {
            openssl_sign($buildStr, $sign, $res, OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
        } else {
            throw new Exception('私钥格式不正确');
        }
        return base64_encode($sign);
    }

    /**
     * RSA验签
     * @param Request $request
     * @param string $public_key 公钥字符串
     * @param string $sign 要校对的的签名结果
     * return 验证结果
     * @return bool
     * @throws Exception
     */
    public function check(array $data, string $public_key, string $sign): bool
    {
        $data = array_except($data, ['sign', 'sign_type']);
        ksort($data);
        $buildStrArr = [];
        foreach ($data as $k => $v) {
            $buildStrArr[] = "$k=$v";
        }
        $buildStr = implode('&', $buildStrArr);
        $search = [
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            "\n",
            "\r",
            "\r\n"
        ];
        $public_key = str_replace($search, '', $public_key);
        $public_key = $search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_publickey($public_key);
        if ($res) {
            $result = (bool)openssl_verify($buildStr, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
        } else {
            throw new Exception('公钥格式有误');
        }
        return $result;
    }
}
