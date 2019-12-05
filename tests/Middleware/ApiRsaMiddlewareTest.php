<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Tests\Middleware;


use App\Models\User;
use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Route;
use Tests\TestCase;
use Zhan3333\ApiRsa\ClientRSA;
use Zhan3333\ApiRsa\Conversions\ConversionResponse;
use Zhan3333\ApiRsa\Exception\MissingException\MissAPPIDException;
use Zhan3333\ApiRsa\Middleware\ApiRsaMiddleware;
use Zhan3333\ApiRsa\Models\ApiRSAKey;
use Zhan3333\ApiRsa\RSA;

class ApiRsaMiddlewareTest extends TestCase
{
    /** @var Generator */
    private $faker;

    private $userPrivateKey = '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDMOVqRT5jNcayp
rpGq0hH4yTlIbC9UOE4/i5TIUYM1H6YDDpjVAyJWS8ns6uu6j1Er0Lw24ducivUd
wtEsNATTxvUUdOvVpx3hiETzyO5Uwfk2lxJ/sVa2jt/LjvOSxsCpt6yD1vPhTggj
E01VpBezLHO8A0muqVzkeYdhlsEpvIiIumjP5yXFTuD4KTYU1qHzsSGMTVYOFs7a
CVnZXAYrRQGNzIKj/+vjM9MI2AFTKwf9kujKZOcbu+z7hY/B6CTBuG82fcrQVDk2
vfPp7Cvm6zK1Ou8HyNjrXC20PGayh9WPeTO+Cc82VlVmXZHeYbRF/OJ4DREsemK2
VfxH8DDHAgMBAAECggEBAIqIPU9k1xpFigJwUI+3gElq9liSSmiu6SVh4cMgyAg5
N7vLIxEb1ycCsi7sDIu4vC8koQf5nadK+4r8pDd/8eZWh9kglvmPK03Zyiw0mLcV
/Z9ySzIjcdbUhCrpBYEDIZ3+4h9sqHKlJA/nAAagERORi9B1yn2YlVLGvXcNdGqC
KdRJPC/ODO4XL/Ni1wW6vxZIMirJGNr/e6ZjYKLrXnb0AcecLJw4mNrtN7ubb1kA
pmNRK8rbEo8N/Jzs1XPK1GZkx9UN0IGF5W3X2SBDBPER5kxHgtDyRi2SRAqPnVNv
xRJs3GLn1f3pvPUFsUPBbGJRqchFvPGyxn3voUK96EECgYEA+9GjK6GOnIz1BHNk
gotU/61TZjl9xzbpJYKJcPbRmk25bsDnxbGh2licfCTFIP+6Nc7XFUNwD9+3kTRj
tFMefWn2hBi9kXI6cH99cDJKuUfhEpmpbSnYCcTTgRJnGoxyh0DZYTb4tx0aVek7
6N2DdTFaeegdO5PLilKs3Y+itxsCgYEAz51pykgJ9ng5QatPrLc94ts3TvROdFPt
uhOlpSDwl53dbp27THJEs642fTb6Wd+3xPAtBUHoL9toxANVt0/UEF7v81oRRZRU
/MwXJsBcFdzemEN51WK5t4S9jRgFzse04fFHhE4xzBlrkdqiYlOjDFaRqYHL9qFk
NsdRCuWja8UCgYAtf24M1w7OSEWIvVSepZAA9g8IfEC2erxbM5+jkmTCjWKAmUgH
FxYYPkRfxcD8OlgpmqqPw/R+Wbxv9thA33e1zyxkJ5gwrEPUyaQfXmbT3SHUW4Ea
ISJQeYiBXJLWYCPdPiFIiEcdxptYhGB3rXYv+W/QcNi/R+/RmSh0i5wpmwKBgQCA
xakdPyt2xUD2O1Ry00E+WSvn+95BhuEXniIK1vCDifYTKyBGHUYBkJfZeGU1o25v
Z2z1Ktjh/hvwIAVlas0kzk3USdQWrRc9qTTe40b6hIFIPenucYqxilKrle/cPsAx
uB7csEdwyX+P+uTnSesCtxsn4QrqUVZAJ8ZblO2U5QKBgBWySwpP25LKDNtBWLrm
8zfUqnSdC5AXENSZ/EYeE5lAg6u/wYFnuPaS6saqjmRFbCXxNey7DGXDbGQQx25i
uUWF+35qlCNqGw9TwySdPrkNkWeAmU24V1Mv2TvI0SLUXLZIABSyKrFX/9NZmxE7
Bd9Nbo8pDaP7K79pQ9QrALw3
-----END PRIVATE KEY-----';
    private $userPublicKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzDlakU+YzXGsqa6RqtIR
+Mk5SGwvVDhOP4uUyFGDNR+mAw6Y1QMiVkvJ7Orruo9RK9C8NuHbnIr1HcLRLDQE
08b1FHTr1acd4YhE88juVMH5NpcSf7FWto7fy47zksbAqbesg9bz4U4IIxNNVaQX
syxzvANJrqlc5HmHYZbBKbyIiLpoz+clxU7g+Ck2FNah87EhjE1WDhbO2glZ2VwG
K0UBjcyCo//r4zPTCNgBUysH/ZLoymTnG7vs+4WPwegkwbhvNn3K0FQ5Nr3z6ewr
5usytTrvB8jY61wttDxmsofVj3kzvgnPNlZVZl2R3mG0RfzieA0RLHpitlX8R/Aw
xwIDAQAB
-----END PUBLIC KEY-----
';

    public function setUp(): void
    {
        parent::setUp();

        Route::any('_test', function () {
            return [
                'success' => 'ok',
            ];
        })->middleware('api_rsa');
        $this->faker = app(Generator::class);
    }

    /**
     * @test
     */
    public function missingAppIdMessing()
    {
        $this->post('_test')
            ->assertStatus(200)
            ->assertJsonStructure([
                config('api-rsa.response.response_name') => [
                    'msg',
                    'code',
                    'sub_msg',
                    'sub_code',
                    'nonce',
                    'biz_content'
                ],
                'sign'
            ])
            ->assertJson([
                config('api-rsa.response.response_name') => [
                    'msg' => MissAPPIDException::MSG,
                    'code' => MissAPPIDException::CODE,
                    'sub_msg' => MissAPPIDException::SUB_MSG,
                    'sub_code' => MissAPPIDException::SUB_CODE,
                    'biz_content' => null,
                ],
            ]);
    }

    /**
     * 测试response签名
     * @test
     */
    public function testResponseSign()
    {
        $response = Response::create([
            'sub_msg' => 'masdlkfjl',
            'sub_code' => '2000',
            'code' => '111',
            'msg' => 'asdfasd',
            'nonce' => 'hh8chMkFcZdm5lhAvNtFWjc0hbtMmrss',
            'biz_content' => json_encode(['test' => 'data'])
        ]);
        $privateKey = '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDCudB5JRZco5g8
V/P6vmc1vlNqeXCOleS5Zeo+Qn9q2J01Lmroujjo/rCXFz994WAWtI+N+ZoC7x/u
i97QLpF/6QCK19OFZ7jEfoCyCXZVyRF6/nrwyAyp42t7qSE1+U1Tfw48+9A1+2wI
7DEHU8PQ9zMBjLg/gOdma6USGTim6Ej673U5P5ADgYaEU3DqQJkelKmhp8eZZskd
aoFIQsI6GCJ1LmHvcw5pjT6W+42R3WKL4ob68qwIIw19keXmDU2K523+u+vwYZfT
FvcT/9mXGEsSkA76qBSuH9Hugf1A3hi24bVUXqsEavHjlk87zA639sHU5gwsKZyM
JdZlnpITAgMBAAECggEAYGkfjtuN9tAIz9e8NKmQ8EsLgewejnoohKKxF6JU89HM
BEi3JgMNqk5voF8iUHEF4jgD24d5eXprlPAroWdtIqd6XUP+tk5kbOU1UvDcZhzn
Sr40oIJrwrGWxeM3TkHgxzZhFlCXBiM1mjVXEQmoKg46csn5b3ejGlKxqARUyE3b
w0p3NEZBT+WTVHwH04dxFvnJxnnZCqXeCmJVO502zyKJUAlt80o10R7zQRlLMHw0
iYDQRo/BKJoe1rW/1bVJ+E8DUZ7NwdfaH3D21FOLc5m36LXiAjWh/PBoQ3EFhQ9/
whCayNtscH2jVtG5poqVboDhegDQceav/wpiCRN0AQKBgQD1Q31S1kNx/ilFlvWq
vtwblgNiUd9iyZhLe13PcWNhCetjpJbjs/xRoe5JF7LzzKVfXZVgycHgNpwyjFG+
Qn2NmvxzMw8w4lMf4YoP4h9xJitlXJZmJJD780d3U2G8oX/ubrqpTOUmtrEWjj/3
tX79bV07VaHXTH0iPMoV7fXR2QKBgQDLP/sDmIcOMXjL1IDlWjsMsaIwFS6laE3q
0oUfrZM9/4Be+94zBgDDoheaW1WhxapW9FF9fK10BVHHXqh/aBDDHKjeQxL77vWB
mx6nBuAoQi2wrIZzYEE5qAR+jUsZ0jM1QHMQX2jwQ8zjQDdpQhoZ7gV0XCfZFMLu
psF8pzSjywKBgQCrzBRy++bOaGvcdPusGHjxUckZC6Rf/DoTVVzGu/QSuvIJH/cZ
lK9/NuBEjrcpEwYboN/LpkeJmHcc6TExBj4P/Kosv206nq3/POqKaagAh+4J2Cr5
bU+pTCWZLezeRnnoN+PERzqUPZAZ7pZcGuPS/NI7h6YPu2JDozUi20Y/MQKBgB/F
DHNvcAzj5sHVoaA4DmrZiUSCyxvHxgYiifR9qTpTqbkvHXhUQ+JQf+f8xtHWl92n
quU2i9ZKzpGfVi80EQqufCbhzSHcJDvyB0SMapVgYsvu68U1Enz0ql8trsOGe8Kj
JFk20KxdrLUI4KBvWK0c63VYwlHIUQAEyNG3QR8pAoGBAK6KYzOLIBHkMTKN8Bfj
OhV8pUp2wDhcdKMjunjnoIiH7N65A40SOF+sq4ntsSilVTillzqYD5JqTQqqcBIO
cPhsbtq4SJ8KS0IS2ZgyffaYTszy3mXTpVFga07/myYMJtM37tfxykALDXHfNZYr
nb6Q3EIXEZlv83/tp0kYmsQy
-----END PRIVATE KEY-----
';
        $middleware = new ApiRsaMiddleware();
        $sign = $middleware->sign($response, $privateKey);
        $this->assertEquals(
            'cY/KsmJggjl2tqyET7Pe0kYoO0X4J9pQScixMJJDXn/WRBZDseLVQqz8zkzrZQWDcpfzbEV5hkAH9lhGtafseAidN8tiiZHt2BytfT1PJ1UhOTCsmQ9UAMQqXqrg6u1Eybb++xTdl5TKLOxLvYP4XumXhupeTD0gLaxBYWcyv7jRSwlT/sJl6ZLo8N4W/lH3PrqPQzppCfOSpgaO0R7KXjifReuBwfMA6ZbqtnxWBxno2QQeL8Dg31G6oZWAX/SHs9d080neoihKnQyVCx3aPFMFNdQeh7dzpWGDRRidg65FhDVogUlo8HXgIqAN7bUdgCROUy/7vHh4JaZGe86vvw==',
            $sign
        );
    }

    /**
     * 测试验证用户请求
     * @test
     */
    public function testRequestVerify()
    {
        $middleware = new ApiRsaMiddleware();
        $params = [
            'app_id' => '123456',
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'sign_type' => 'RSA2',
            'sign' => '',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode([
                'test' => 'data'
            ]),
        ];

        // sign
        $params['sign'] = app(ClientRSA::class)->sign($params, $this->userPrivateKey);
        $request = Request::create(
            'test',
            'post',
            $params
        );
        $verify = $middleware->verify($request, $this->userPublicKey);
        $this->assertEquals(true, $verify);
    }

    /**
     * 测试RSA
     * @test
     */
    public function testRSA()
    {
        [$publicKey, $privateKey] = app(RSA::class)->generate();
        $str = '123456';
        $sign = app(RSA::class)->sign($str, $privateKey);
        $verity = app(RSA::class)->check($str, $publicKey, $sign);
        $this->assertEquals(true, $verity);
    }

    /**
     * 测试生成 RSA 公私钥加密解密
     * @test
     */
    public function testGenerateRSA()
    {
        [$pubKey, $privKey] = app(RSA::class)->generate();

        $data = 'plaintext data goes here';

        // Encrypt the data to $encrypted using the public key
        openssl_public_encrypt($data, $encrypted, $pubKey);

        // Decrypt the data using the private key and store the results in $decrypted
        openssl_private_decrypt($encrypted, $decrypted, $privKey);


        $this->assertEquals($data, $decrypted);
    }

    /**
     * @test
     */
    public function sendSuccessData()
    {
        $email = $this->faker->safeEmail;
        $phone = $this->faker->phoneNumber;
        \Artisan::call('api-rsa', [
            'action' => 'add',
            '--name' => $this->faker->name,
            '--email' => $email,
            '--phone' => $phone,
            '--user_public_key' => $this->userPublicKey,
        ]);
        $userId = User::where('email', $email)
            ->where('phone', $phone)
            ->value('id');
        $apiRSAKey = ApiRSAKey::where('user_id', $userId)->first();
        $params = [
            'app_id' => $apiRSAKey->app_id,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'version' => '1.0',
            'sign_type' => 'RSA2',
            'charset' => 'UTF-8',
            'format' => 'JSON',
            'nonce' => Str::random(32),
        ];
        $sign = app(ClientRSA::class)->sign($params, $this->userPrivateKey);
        $params['sign'] = $sign;
        $response = $this->post('_test', $params)
            ->assertStatus(200)
            ->assertJsonStructure([
                config('api-rsa.response.response_name') => [
                    'msg',
                    'code',
                    'sub_msg',
                    'sub_code',
                    'biz_content'
                ],
                'sign'
            ])
            ->assertJson([
                config('api-rsa.response.response_name') => [
                    'msg' => ConversionResponse::MSG,
                    'code' => ConversionResponse::CODE,
                    'sub_msg' => ConversionResponse::SUB_MSG,
                    'sub_code' => ConversionResponse::SUB_CODE,
                    'biz_content' => json_encode(['success' => 'ok']),
                ],
            ]);
        // verify sign
        $check = app(ClientRSA::class)->check($response->json(config('api-rsa.response.response_name')), $apiRSAKey->system_public_key, $response->json('sign'));
        $this->assertEquals(true, $check);
    }
}
