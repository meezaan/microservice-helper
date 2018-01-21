<?php

namespace Meezaan\MicroServiceHelper;

use Meezaan\MicroServiceHelper\HttpCodes;

/**
 * Class Response
 * @package Meezaan\MicroServiceHelper\Response
 */
class Response
{
    /**
     * [build description]
     * @param  [type] $data   [description]
     * @param  [type] $code   [description]
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public static function build($data, $code)
    {
        return
            [
                'code' => $code,
                'status' => HttpCodes::getCode($code),
                'data' => $data
            ];
    }
}
