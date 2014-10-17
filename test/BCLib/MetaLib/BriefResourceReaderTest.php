<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Resource;

class BriefResourceReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReadsCorrectly()
    {
        $reader = new BriefResourceReader();

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

        $resource_xml = simplexml_load_file(__DIR__ . '/../../fixtures/resources-02.xml');

        $this->assertEquals($resource_list, $reader->read($resource_xml));
    }
}
 