<?php

namespace BCLib\MetaLib;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesRetrievable()
    {
        $op = 'baz';
        $params = ['foo' => 'bar'];
        $reader = $this->getMockBuilder("\BCLib\MetaLib\Readers\ResponseReader")->getMock();
        $command = new Command($op, $params, true, $reader);

        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals(true, $command->require_login);
    }

    public function testResponseReaderReads()
    {
        $xml = new \SimpleXMLElement('<foo />');

        $op = 'baz';
        $params = ['foo' => 'bar'];
        $reader = $this->getMockBuilder("\BCLib\MetaLib\Readers\ResponseReader")->getMock();
        $reader->expects($this->once())
            ->method('read')
            ->with($this->equalTo($xml));
        $command = new Command($op, $params, true, $reader);

        $command->read($xml);
    }

    public function testErrorListenerCalled()
    {
        $op = 'baz';
        $params = ['foo' => 'bar'];
        $reader = $this->getMockBuilder("\BCLib\MetaLib\Readers\ResponseReader")->getMock();
        $command = new Command($op, $params, true, $reader);

        $command->addErrorListener(
            '00001',
            function ($message, $url) {
                return "$message:$url";
            }
        );

        $this->assertEquals('message:error', $command->notify('00001', 'message', 'error'));
    }
}
 