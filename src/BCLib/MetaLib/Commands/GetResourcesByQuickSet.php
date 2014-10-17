<?php

namespace BCLib\MetaLib\Commands;

use BCLib\MetaLib\ResourceReader;

class GetResourcesByQuickSet extends ResourceSearch
{
    /**
     * @var ResourceReader
     */
    private $reader;

    public function __construct($quickset_id, ResourceReader $reader)
    {
        $op = 'retrieve_resources_by_quick_set_request';
        $params = [
            'quick_sets_id' => $quickset_id
        ];
        parent::__construct($op, $params, true);

        $this->reader = $reader;
    }

    public function read(\SimpleXMLElement $xml)
    {
        return $this->reader->read($xml->children()[0]->set_info);
    }
}