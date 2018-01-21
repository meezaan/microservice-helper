<?php
$x = (realpath(__DIR__ . '/../../vendor/autoload.php'));
require_once($x);

use \Meezaan\MicroServiceHelper\HttpCodes;

class HttpCodesTest extends PHPUnit\Framework\TestCase
{
    public function testGetCode()
    {
        $this->assertEquals('OK', HttpCodes::getCode(200));
        $this->assertEquals('UNAUTHORIZED', HttpCodes::getCode(401));
        $this->assertEquals('BAD REQUEST', HttpCodes::getCode(400));
    }
}
