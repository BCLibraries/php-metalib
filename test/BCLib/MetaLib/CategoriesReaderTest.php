<?php
/**
 * Created by PhpStorm.
 * User: florinb
 * Date: 6/2/14
 * Time: 8:10 PM
 */

namespace BCLib\MetaLib;


class CategoriesReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReadReadsCategories()
    {
        $categories = new CategoryContainer();

        $category_one = new Category();
        $category_one->name= 'Reference';

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

        $reader = new CategoriesReader();

        $xml = simplexml_load_file(__DIR__ . '/../../fixtures/categories-01.xml');

        $this->assertEquals($categories, $reader->read($xml));
    }
}
 