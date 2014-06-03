<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Commands\LoginCommand;

class LoginCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesRetrievable()
    {
        $op = 'login_request';
        $params = [
            'user_name'     => 'user',
            'user_password' => 'passwd',
            'requester_ip'  => '167.0.0.1'
        ];
        $reader = $this->getMockBuilder("\BCLib\MetaLib\Readers\LoginReader")->getMock();
        $command = new LoginCommand('user', 'passwd', '167.0.0.1', $reader);

        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals(false, $command->require_login);
    }

    public function testReadsValidResponseCorrectly()
    {
        $xml = simplexml_load_file(__DIR__ . '/../../../fixtures/valid-login-01.xml');
        $command = new LoginCommand('user', 'passwd', '167.0.0.1');
        $this->assertEquals('XHU95VLSX1NGLA6HQPVKTU5H8FDH6A65UT61QD36JB6F2VSJ47', $command->read($xml));
    }

    /**
     * @expectedException \Exception
     */
    public function testReadsInvalidLoginCorrectly()
    {
        $xml = simplexml_load_file(__DIR__ . '/../../../fixtures/invalid-login-01.xml');
        $command = new LoginCommand('user', 'passwd', '167.0.0.1');
        $command->read($xml);
    }
}
 