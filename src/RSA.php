<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa;


use Exception;
use Illuminate\Support\Str;

class RSA
{
    /**
     * RSA签名
     * @param string $data 待签名数据
     * @param string $private_key 私钥字符串
     * return 签名结果
     * @return string
     * @throws Exception
     */
    public function sign(string $data, string $private_key): string
    {

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
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
        } else {
            throw new Exception('私钥格式不正确');
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param string $data 待签名数据
     * @param string $public_key 公钥字符串
     * @param string $sign 要校对的的签名结果
     * return 验证结果
     * @return bool
     * @throws Exception
     */
    public function check(string $data, string $public_key, string $sign): bool
    {
        $search = [
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            "\n",
            "\r",
            "\r\n"
        ];
        $public_key = str_replace($search, '', $public_key);
        $public_key = $search[0] . PHP_EOL . chunk_split($public_key, 64, "\n") . $search[1] . "\n";
        $res = openssl_pkey_get_public($public_key);
        if ($res) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
        } else {
            throw new Exception('公钥格式有误');
        }
        return $result;
    }

    public function generate()
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $res = openssl_pkey_new($config);
        $privateKey = '';
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];
        return [$publicKey, $privateKey];
    }
}
