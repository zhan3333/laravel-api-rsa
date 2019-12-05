<?php
/**
 * User: zhan
 * Date: 2019/9/30
 * Email: <grianchan@gmail.com>
 */

namespace Zhan3333\ApiRsa\Helper;

use Response;
use Zhan3333\ApiRsa\Conversions\ConversionResponse;

trait ResponseHelper
{
    public function success($body = null)
    {
        return $body;
    }
}
