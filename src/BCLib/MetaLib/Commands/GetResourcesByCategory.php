<?php

namespace BCLib\MetaLib\Commands;

class GetResourcesByCategory extends ResourceSearch
{
    public function __construct($category_id)
    {
        $op = 'retrieve_resources_by_category_request';
        $params = [
            'category_id' => $category_id
        ];
        parent::__construct($op, $params, true);
    }

    public function read(\SimpleXMLElement $xml)
    {
        return $this->_readResourceList($xml->children()[0]);
    }
}