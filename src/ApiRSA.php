<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa;


use Illuminate\Support\Arr;
use Zhan3333\ApiRsa\Exception\SystemException\PrivateKeyFormatErrorException;
use Zhan3333\ApiRsa\Exception\SystemException\PublicKeyFormatErrorException;

/**
 * Api 加签解签
 * Class ApiRSA
 * @package Zhan3333\ApiRsa
 */
class ApiRSA
{
    /**
     * RSA签名
     * @param array $params
     * @param string $private_key 私钥字符串
     * return 签名结果
     * @return string
     * @throws PrivateKeyFormatErrorException
     */
    public function sign(array $params, $private_key): string
    {
        $all = Arr::except($params, ['sign', 'sign_type']);
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
        $res = openssl_pkey_get_private($private_key);

        if ($res) {
            openssl_sign($buildStr, $sign, $res, OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
        } else {
            throw new PrivateKeyFormatErrorException('私钥格式不正确');
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param array $params
     * @param string $public_key 公钥字符串
     * @param string $sign 要校对的的签名结果
     * return 验证结果
     * @return bool
     * @throws PublicKeyFormatErrorException
     */
    public function check(array $params, string $public_key, string $sign): bool
    {
        $all = Arr::except($params, ['sign', 'sign_type']);
        ksort($all);
        $buildStrArr = [];
        foreach ($all as $k => $v) {
            $buildStrArr[] = "$k=$v";
        }
        $buildStr = implode('&', $buildStrArr);
        dump($buildStr);
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
            throw new PublicKeyFormatErrorException('公钥格式有误');
        }
        return $result;
    }
}
