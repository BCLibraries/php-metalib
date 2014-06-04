<?php

use BCLib\MetaLib\Models\Category;
use BCLib\MetaLib\CategoryContainer;
use BCLib\MetaLib\Commands\GetCategories;
use BCLib\MetaLib\Models\Subcategory;

class GetCategoriesTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectAttributesAreSet()
    {
        $op = 'retrieve_categories_request';
        $params = [
            'requester_ip' => '167.0.0.7'
        ];
        $require_login = true;

        $command = new GetCategories('167.0.0.7');
        $this->assertEquals($op, $command->op);
        $this->assertEquals($params, $command->params);
        $this->assertEquals($require_login, $command->require_login);
    }

    public function testReadReadsCategories()
    {
        $categories = new CategoryContainer();

        $category_one = new Category();
        $category_one->name = 'Reference';

        $subcat_one = new Subcategory();
        $subcat_one->name = 'ALL';
        $subcat_one->bases = '000000000';
        $subcat_one->sequence = '000001313';
        $category_one->subcategories[] = $subcat_one;

        $subcat_two = new Subcategory();
        $subcat_two->name = 'Biography';
        $subcat_two->bases = '000000000';
        $subcat_two->sequence = '000001315';
        $category_one->subcategories[] = $subcat_two;

        $category_two = new Category();
        $category_two->name = 'Interdisciplinary';

        $subcat_three = new Subcategory();
        $subcat_three->name = 'General';
        $subcat_three->bases = '000000003';
        $subcat_three->sequence = '000000413';
        $category_two->subcategories[] = $subcat_three;

        $categories->add($category_one);
        $categories->add($category_two);

        $command = new GetCategories();

        $xml = simplexml_load_file(__DIR__ . '/../../../fixtures/categories-01.xml');

        $this->assertEquals($categories, $command->read($xml));
    }
}
 