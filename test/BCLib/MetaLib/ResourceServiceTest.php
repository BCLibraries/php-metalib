<?php

namespace BCLib\MetaLib;


class ResourceServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_client;

    public function setUp()
    {
        $this->_client = $this->getMockBuilder('\BCLib\MetaLib\Client')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testRetrieveByCategorySendsCorrectParameters()
    {
        $response = $this->_loadXML('resources-01.xml');

        $params = [
            'category_id' => 'foo'
        ];

        $this->_client->expects($this->once())
            ->method('send')
            ->with('retrieve_resources_by_category_request', $params, true)
            ->will($this->returnValue($response));

        $resources = new ResourceService($this->_client);
        $resources->retrieveByCategory('foo');
    }

    public function testRetrieveByCategoryRetrieves()
    {
        $expected = [];

        $source = new Resource();
        $source->internal_number = '000003209';
        $source->number = 'BCL03643';
        $source->name = 'Database Number One';
        $source->short_name = 'Database One';
        $source->searchable = false;
        $expected [] = $source;

        $source = new Resource();
        $source->internal_number = '000007958';
        $source->number = 'BCL06327';
        $source->name = 'Database Number Two';
        $source->short_name = 'Database Two';
        $source->searchable = true;
        $expected [] = $source;

        $response = $this->_loadXML('resources-01.xml');

        $this->_client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $resources = new ResourceService($this->_client);
        $this->assertEquals($expected, $resources->retrieveByCategory('foo'));
    }

    public function testDefaultParamsAreSet()
    {
        $response = $this->_loadXML('resources-01.xml');

        $params = [
            'category_id'  => 'foo',
            'requester_ip' => '127.0.0.1',
            'institute'    => 'bar'
        ];

        $this->_client->expects($this->once())
            ->method('send')
            ->with('retrieve_resources_by_category_request', $params, true)
            ->will($this->returnValue($response));

        $resources = new ResourceService($this->_client, '127.0.0.1', 'bar');
        $resources->retrieveByCategory('foo');
    }

    public function testRetrieveCategoriesSendsCorrectParameters()
    {
        $response = $this->_loadXML('categories-01.xml');

        $params = [];

        $this->_client->expects($this->once())
            ->method('send')
            ->with('retrieve_categories_request', $params, true)
            ->will($this->returnValue($response));

        $resources = new ResourceService($this->_client);
        $resources->retrieveCategories();
    }

    public function testRetrtieveCategoriesRetrieves()
    {
        $expected = [];

        $category = new Category();
        $category->name = 'Reference';
        $category->subcategories = [
            $this->_createSubCategory('ALL','000000000','000001313'),
            $this->_createSubCategory('Biography','000000000','000001315')
        ];
        $expected[] = $category;

        $category = new Category();
        $category->name = 'Interdisciplinary';
        $category->subcategories = [
            $this->_createSubCategory('General','000000003','000000413'),
        ];
        $expected[] = $category;

        $response = $this->_loadXML('categories-01.xml');

        $this->_client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $resources = new ResourceService($this->_client);
        $this->assertEquals($expected, $resources->retrieveCategories());
    }

    protected function _loadXML($file)
    {
        return simplexml_load_file(__DIR__ . '/../../fixtures/' . $file);
    }

    protected function _createSubCategory($name, $base, $sequence)
    {
        $sub = new Subcategory();
        $sub->name = $name;
        $sub->bases = $base;
        $sub->sequence = $sequence;
        return $sub;
    }
}
 