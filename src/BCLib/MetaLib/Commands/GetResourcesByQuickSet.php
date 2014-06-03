<?php

namespace BCLib\MetaLib\Commands;

class GetResourcesByQuickSet extends ResourceSearch
{
    public function __construct($quickset_id)
    {
        $op = 'retrieve_resources_by_quick_set_request';
        $params = [
            'quick_sets_id' => $quickset_id
        ];
        parent::__construct($op, $params, true);
    }

    public function read(\SimpleXMLElement $xml)
    {
        return $this->_readResourceList($xml->children()[0]->set_info);
    }
}