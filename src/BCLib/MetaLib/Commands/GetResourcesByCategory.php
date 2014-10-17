<?php

namespace BCLib\MetaLib\Commands;

use BCLib\MetaLib\ResourceReader;

class GetResourcesByCategory extends ResourceSearch
{
    /**
     * @var ResourceReader
     */
    private $reader;

    public function __construct(ResourceReader $reader)
    {
        $op = 'retrieve_resources_by_category_request';
        $params = [];

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

        $this->reader = $reader;
    }

    public function setCategoryId($cat_id)
    {
        $this->_params['category_id'] = $cat_id;
    }

    public function read(\SimpleXMLElement $xml)
    {
        return $this->reader->read($xml);
    }

    public function fullInfo($get_full_info = true)
    {
        $this->_params['source_full_info_flag'] = $get_full_info ? 'Y' : 'N';
    }

    public function doNothing()
    {
        //
    }
}