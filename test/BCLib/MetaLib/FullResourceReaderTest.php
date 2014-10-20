<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Keyword;
use BCLib\MetaLib\Models\Resource;

class FullResourceReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReadsCorrectly()
    {
        $reader = new FullResourceReader();

        $expected = [];
        $expected[0] = new Resource();
        $expected[0]->internal_number = '000003053';
        $expected[0]->number = 'BCL01547';
        $expected[0]->name = 'Database One';
        $expected[0]->short_name = 'DB One';
        $expected[0]->searchable = true;
        $expected[0]->description = '';

        $expected[1] = new Resource();
        $expected[1]->internal_number = '000001509';
        $expected[1]->number = 'BCL01892';
        $expected[1]->name = 'Database Two';
        $expected[1]->short_name = 'DB Two';
        $expected[1]->searchable = false;
        $expected[1]->description = 'Description of DB Two.';

        $keyword = new Keyword();
        $keyword->term = 'Asian Languages and Cultures';
        $expected[1]->addKeyword($keyword);

        $keyword = new Keyword();
        $keyword->term = 'Chinese Studies';
        $expected[1]->addKeyword($keyword);

        $resource_xml = simplexml_load_file(__DIR__ . '/../../fixtures/resources-full-01.xml');

        $result = $reader->read($resource_xml);

        $this->assertEquals($expected, $result);
        $this->assertEquals($expected[1]->keywords[0]->term, $result[1]->keywords[0]->term);
        $this->assertEquals($expected[1]->keywords[1]->term, $result[1]->keywords[1]->term);

    }

    public function testDontAddDuplicateKeywords()
    {
        $reader = new FullResourceReader();

        $expected = [];

        $expected[0] = new Resource();
        $expected[0]->internal_number = '000001509';
        $expected[0]->number = 'BCL01892';
        $expected[0]->name = 'Database Two';
        $expected[0]->short_name = 'DB Two';
        $expected[0]->searchable = false;
        $expected[0]->description = 'Description of DB Two.';

        $keyword = new Keyword();
        $keyword->term = 'Asian Languages and Cultures';
        $expected[0]->addKeyword($keyword);

        $keyword = new Keyword();
        $keyword->term = 'Chinese Studies';
        $expected[0]->addKeyword($keyword);

        $resource_xml = simplexml_load_file(__DIR__ . '/../../fixtures/resources-full-duplicate-key-01.xml');

        $result = $reader->read($resource_xml);

        $this->assertEquals($expected, $result);
        $this->assertEquals($expected[0]->keywords[0]->term, $result[0]->keywords[0]->term);
        $this->assertEquals(2, count($expected[0]->keywords));

    }
}
 