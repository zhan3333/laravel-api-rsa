<?php
/**
 * User: zhan
 * Date: 2019/9/29
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiRSAKey extends Model
{
    use SoftDeletes;

    protected $table = 'api_rsa_keys';

    protected $guarded = [];
}
