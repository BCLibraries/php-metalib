<?php

namespace BCLib\MetaLib\Commands;

use BCLib\MetaLib\Models\Category;
use BCLib\MetaLib\CategoryContainer;
use BCLib\MetaLib\Command;
use BCLib\MetaLib\Models\Subcategory;

class GetCategories extends Command
{
    public function __construct($requester_ip = '127.0.0.1')
    {
        $op = 'retrieve_categories_request';
        $params = ['requester_ip' => $requester_ip];
        parent::__construct($op, $params, true);
    }

    public function read(\SimpleXMLElement $xml)
    {
        $category_list = new CategoryContainer();
        $list_xml = $xml->retrieve_categories_response;
        foreach ($list_xml->category_info as $xml) {
            $category = new Category();
            $category->name = (string) $xml->category_name;
            foreach ($xml->subcategory_info as $subcategory_xml) {
                $category->subcategories[] = $this->_loadSubCategories($subcategory_xml, $category);
            }
            $category_list->add($category);
        }
        return $category_list;
    }

    protected function _loadSubCategories(\SimpleXMLElement $subcategory_xml, Category $parent)
    {
        $subcategory = new Subcategory();
        $subcategory->name = (string) $subcategory_xml->subcategory_name;
        $subcategory->sequence = (string) $subcategory_xml->sequence;
        $subcategory->bases = (string) $subcategory_xml->no_bases;
        $subcategory->category = $parent;
        return $subcategory;
    }
}