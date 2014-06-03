<?php

namespace BCLib\MetaLib;

class CategoriesReader implements ResponseReader
{

    public function read(\SimpleXMLElement $xml)
    {
        $category_list = new CategoryContainer();
        $list_xml = $xml->retrieve_categories_response;
        foreach ($list_xml->category_info as $xml) {
            $category = new Category();
            $category->name = (string) $xml->category_name;
            foreach ($xml->subcategory_info as $subcategory_xml) {
                $category->subcategories[] = $this->_loadSubCategories($subcategory_xml);
            }
            $category_list->add($category);
        }
        return $category_list;
    }

    protected function _loadSubCategories(\SimpleXMLElement $subcategory_xml)
    {
        $subcategory = new Subcategory();
        $subcategory->name = (string) $subcategory_xml->subcategory_name;
        $subcategory->sequence = (string) $subcategory_xml->sequence;
        $subcategory->bases = (string) $subcategory_xml->no_bases;
        return $subcategory;
    }
}