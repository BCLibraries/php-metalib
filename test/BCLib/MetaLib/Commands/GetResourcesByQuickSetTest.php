<?php

use BCLib\MetaLib\Commands\GetResourcesByQuickSet;
use BCLib\MetaLib\Models\Resource;

class GetResourcesByQuickSetTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesAreSet()
    {
        $op = 'retrieve_resources_by_quick_set_request';
        $params = [
            'quick_sets_id' => '00000035'
        ];
        $require_login = true;

        $command = new GetResourcesByQuickSet('00000035', $this->getReader());
        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals($require_login, $command->require_login);
    }

    public function testReadQuicksetResponseReturnsResourceList()
    {
        $resource_xml = simplexml_load_file(__DIR__ . '/../../../fixtures/resources-02.xml');

        $reader = $this->getReader();
        $reader->expects($this->once())
            ->method('read')
            ->will($this->returnValue('read-response'));

        $command = new GetResourcesByQuickSet('0000001', $reader);
        $this->assertEquals('read-response', $command->read($resource_xml));
    }

    public function getReader()
    {
        return $this->getMock('\BCLib\MetaLib\ResourceReader');
    }
}