<?php

use BCLib\MetaLib\Commands\GetResourcesByCategory;
use BCLib\MetaLib\Models\Resource;

class GetResourcesByCategoryTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesAreSet()
    {
        $op = 'retrieve_resources_by_category_request';
        $params = [
            'category_id' => '00000035'
        ];
        $require_login = true;

        $command = new GetResourcesByCategory($this->getReader());
        $command->setCategoryId('00000035');
        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals($require_login, $command->require_login);
    }

    public function testReadQuicksetResponseReturnsResourceList()
    {
        $resource_xml = simplexml_load_file(__DIR__ . '/../../../fixtures/resources-01.xml');

        $reader = $this->getReader();
        $reader->expects($this->once())
            ->method('read')
            ->will($this->returnValue('read-response'));

        $command = new GetResourcesByCategory($reader);
        $this->assertEquals('read-response', $command->read($resource_xml));
    }

    public function getReader()
    {
        return $this->getMock('\BCLib\MetaLib\ResponseReader');
    }
}
 