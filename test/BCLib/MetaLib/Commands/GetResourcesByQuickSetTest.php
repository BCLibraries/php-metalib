<?php

use BCLib\MetaLib\Commands\GetResourcesByCategory;
use BCLib\MetaLib\Commands\GetResourcesByQuickSet;
use BCLib\MetaLib\Resource;

class GetResourcesByQuickSetTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesAreSet()
    {
        $op = 'retrieve_resources_by_quick_set_request';
        $params = [
            'quick_sets_id' => '00000035'
        ];
        $require_login = true;

        $command = new GetResourcesByQuickSet('00000035');
        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals($require_login, $command->require_login);
    }

    public function testReadQuicksetResponseReturnsResourceList()
    {
        $resource_list = [];
        $resource_list[0] = new Resource();
        $resource_list[0]->internal_number = '000001807';
        $resource_list[0]->number = 'BCL02374';
        $resource_list[0]->name = 'Database Number One';
        $resource_list[0]->short_name = 'Database One';
        $resource_list[0]->searchable = false;

        $resource_list[1] = new Resource();
        $resource_list[1]->internal_number = '000001845';
        $resource_list[1]->number = 'BCL02363';
        $resource_list[1]->name = 'Database Number Two';
        $resource_list[1]->short_name = 'Database Two';
        $resource_list[1]->searchable = true;

        $resource_xml = simplexml_load_file(__DIR__ . '/../../../fixtures/resources-02.xml');

        $command = new GetResourcesByQuickSet('0000001');

        $this->assertEquals($resource_list, $command->read($resource_xml));
    }
}