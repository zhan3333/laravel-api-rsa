<?php

namespace Zhan3333\ApiRsa\Middleware;

use App\Exceptions\Handler;
use App\Models\User;
use Cache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\Response;
use Zhan3333\ApiRsa\ApiRSA;
use Zhan3333\ApiRsa\Conversions\ConversionRequest;
use Zhan3333\ApiRsa\Conversions\ConversionResponse;
use Zhan3333\ApiRsa\Exception\ApiRSAException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\APPIDNotRegisteredException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\DuplicateRequestException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidCharsetException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidFormatException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidNonceException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidSignException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidSignTypeException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidTimestampException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\InvalidVersionException;
use Zhan3333\ApiRsa\Exception\InvalidParamsException\TimeoutException;
use Zhan3333\ApiRsa\Exception\MissingException\MissAPPIDException;
use Zhan3333\ApiRsa\Exception\MissingException\MissCharsetException;
use Zhan3333\ApiRsa\Exception\MissingException\MissFormatException;
use Zhan3333\ApiRsa\Exception\MissingException\MissNonceException;
use Zhan3333\ApiRsa\Exception\MissingException\MissSignException;
use Zhan3333\ApiRsa\Exception\MissingException\MissSignPublicKeyException;
use Zhan3333\ApiRsa\Exception\MissingException\MissSignTypeException;
use Zhan3333\ApiRsa\Exception\MissingException\MissTimestampException;
use Zhan3333\ApiRsa\Exception\MissingException\MissVersionException;
use Zhan3333\ApiRsa\Exception\SystemException\SystemErrorException;
use Zhan3333\ApiRsa\Models\ApiRSAKey;

/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */
class ApiRsaMiddleware
{
    /** @var ApiRSAKey */
    private $apiRSAKey;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $this->checkParams($request);
            \Auth::setUser(User::find($this->apiRSAKey->user_id));
            $response = ConversionResponse::handle($next(ConversionRequest::handle($request)));
        } catch (ValidationException $exception) {
            $message = $exception->validator->errors()->first();
            $e = new \Zhan3333\ApiRsa\Exception\BusinessException\ValidationException($message);
            $response = $e->response();
        } catch (ApiRSAException $apiRSAException) {
            $response = $apiRSAException->response();
        } catch (\Error $exception) {
            app(Handler::class)->report(new FatalThrowableError($exception));
            $response = (new SystemErrorException())->response();
        } catch (\Exception $exception) {
            app(Handler::class)->report($exception);
            $response = (new SystemErrorException())->response();
        }
        return $this->createSignResponse($response);
    }

    public function createSignResponse(Response $response): Response
    {
        $systemPrivateKey = $this->apiRSAKey->system_private_key ?? '';
        $sign = $this->sign($response, $systemPrivateKey);
        $content = json_decode($response->getContent(), true);
        return response()->json([
            config('api-rsa.response.response_name') => $content,
            'sign' => $sign,
        ]);
    }

    /**
     * @param Request $request
     * @throws APPIDNotRegisteredException
     * @throws DuplicateRequestException
     * @throws InvalidCharsetException
     * @throws InvalidFormatException
     * @throws InvalidSignException
     * @throws InvalidSignTypeException
     * @throws InvalidTimestampException
     * @throws InvalidVersionException
     * @throws MissAPPIDException
     * @throws MissCharsetException
     * @throws MissFormatException
     * @throws MissSignException
     * @throws MissSignPublicKeyException
     * @throws MissTimestampException
     * @throws MissVersionException
     * @throws TimeoutException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws MissSignTypeException
     * @throws \Zhan3333\ApiRsa\Exception\SystemException\PublicKeyFormatErrorException
     */
    public function checkParams(Request $request): void
    {
        // missing
        if (!$appId = $request->input('app_id')) {
            throw new MissAPPIDException();
        }
        if (!$sign = $request->input('sign')) {
            throw new MissSignException();
        }
        if (!$timestamp = $request->input('timestamp')) {
            throw new MissTimestampException();
        }
        if (!$version = $request->input('version')) {
            throw new MissVersionException();
        }
        if (!$signType = $request->input('sign_type')) {
            throw new MissSignTypeException();
        }
        if (!$charset = $request->input('charset')) {
            throw new MissCharsetException();
        }
        if (!$format = $request->input('format')) {
            throw new MissFormatException();
        }
        if (!$nonce = $request->input('nonce')) {
            throw new MissNonceException();
        }
        if (!$apiRSAKey = config('api-rsa.models.api_rsa')::where('app_id', $appId)->first()) {
            throw new APPIDNotRegisteredException();
        }
        if (!$userPublicKey = $apiRSAKey->user_public_key) {
            throw new MissSignPublicKeyException();
        }

        // invalid
        $timestamp = Carbon::make($timestamp);
        if ($timestamp === null) {
            throw new InvalidTimestampException('无效的 timestamp');
        }
        if ($timestamp->lessThan(now()->subSeconds(config('api-rsa.request.timeout')))) {
            throw new TimeoutException();
        }
        if (!in_array($version, config('api-rsa.request.allow_version'), true)) {
            throw new InvalidVersionException();
        }
        if (!in_array($signType, config('api-rsa.request.allow_sign_type'), true)) {
            throw new InvalidSignTypeException();
        }
        if (!in_array($charset, config('api-rsa.request.allow_charset'), true)) {
            throw new InvalidCharsetException();
        }
        if (!in_array($format, config('api-rsa.request.allow_format'), true)) {
            throw new InvalidFormatException();
        }
        if (!is_string($nonce) && Str::length($nonce) !== 32) {
            throw new InvalidNonceException();
        }
        if (!$this->verify($request, $apiRSAKey->user_public_key)) {
            throw new InvalidSignException();
        }
        $sign = $request->input('sign');
        if (Cache::tags(config('api-rsa.request.tags'))->get(md5($sign))) {
            throw new DuplicateRequestException();
        } else {
            Cache::tags(config('api-rsa.request.tags'))->set(md5($sign), '', config('api-rsa.request.duplicate_request_cache'));
        }
        $this->apiRSAKey = $apiRSAKey;
    }

    /**
     * 加签
     * @param Response $response
     * @param string $privateKey
     * @return mixed
     * @throws \Zhan3333\ApiRsa\Exception\SystemException\PrivateKeyFormatErrorException
     */
    public function sign(Response $response, string $privateKey): string
    {
        if (empty($privateKey)) {
            return '';
        }
        return app(ApiRSA::class)->sign(json_decode($response->getContent(), true), $privateKey);
    }

    /**
     * 验签
     * @param Request $request
     * @param $publicKey
     * @return bool
     * @throws \Zhan3333\ApiRsa\Exception\SystemException\PublicKeyFormatErrorException
     */
    public function verify(Request $request, $publicKey): bool
    {
        return app(ApiRSA::class)->check($request->all(), $publicKey, $request->input('sign'));
    }
}
