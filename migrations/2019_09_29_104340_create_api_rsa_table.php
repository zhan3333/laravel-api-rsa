<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRsaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_rsa_keys', function (Blueprint $table) {
            $table->string('app_id')->primary()->comment('APPID');
            $table->bigInteger('user_id')->comment('用户ID');
            $table->string('user_public_key', 500)->default('')->comment('用户公钥');
            $table->string('system_public_key', 500)->comment('系统公钥');
            $table->string('system_private_key', 2000)->comment('系统私钥');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['app_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_rsa_keys');
    }
}
