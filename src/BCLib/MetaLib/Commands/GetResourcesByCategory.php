<?php

namespace BCLib\MetaLib\Commands;

class GetResourcesByCategory extends ResourceSearch
{
    public function __construct($category_id = null)
    {
        $op = 'retrieve_resources_by_category_request';
        $params = [
            'category_id' => $category_id
        ];

        $return_empty = function () {
            $xml = <<<XML
<x_server_response metalib_version="4.5.2 (790)">
    <retrieve_resources_by_category_response>
    </retrieve_resources_by_category_response>
</x_server_response>
XML;
            return simplexml_load_string($xml);
        };

        $this->addErrorListener('6022', $return_empty);
        $this->addErrorListener('6023', $return_empty);

        parent::__construct($op, $params, true);
    }

    public function setCategoryId($cat_id)
    {
        $this->_params['category_id'] = $cat_id;
    }

    public function read(\SimpleXMLElement $xml)
    {
        return $this->_readResourceList($xml->children()[0]);
    }

    public function doNothing()
    {
        //
    }
}