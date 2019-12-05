<?php
/**
 * User: zhan
 * Date: 2019/9/27
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa;

use Illuminate\Support\ServiceProvider;
use Zhan3333\ApiRsa\Commands\ApiRSAUserCommand;

class ApiRsaProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }
        $this->publishes([
            __DIR__ . '/../config/api-rsa.php' => config_path('api-rsa.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/api-rsa.php', 'api-rsa'
        );
        $this->app->singleton(RSA::class, function () {
            return new RSA();
        });
        $this->app->singleton(ApiRSALog::class, function () {
            return new ApiRSALog();
        });
    }
}
