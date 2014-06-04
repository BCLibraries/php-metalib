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

        $command = new GetResourcesByCategory('00000035');
        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals($require_login, $command->require_login);
    }

    public function testReadReturnsResourceList()
    {
        $resource_list = [];
        $resource_list[0] = new Resource();
        $resource_list[0]->internal_number = '000003209';
        $resource_list[0]->number = 'BCL03643';
        $resource_list[0]->name = 'Database Number One';
        $resource_list[0]->short_name = 'Database One';
        $resource_list[0]->searchable = false;

        $resource_list[1] = new Resource();
        $resource_list[1]->internal_number = '000007958';
        $resource_list[1]->number = 'BCL06327';
        $resource_list[1]->name = 'Database Number Two';
        $resource_list[1]->short_name = 'Database Two';
        $resource_list[1]->searchable = true;

        $command = new GetResourcesByCategory('000000001');

        $resource_xml = simplexml_load_file(__DIR__ . '/../../../fixtures/resources-01.xml');

        $this->assertEquals($resource_list, $command->read($resource_xml));
    }
}
 