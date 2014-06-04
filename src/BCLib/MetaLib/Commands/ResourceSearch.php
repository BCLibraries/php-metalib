<?php

namespace BCLib\MetaLib\Commands;

use BCLib\MetaLib\Command;
use BCLib\MetaLib\Models\Resource;

abstract class ResourceSearch extends Command
{
    protected function _readResourceList(\SimpleXMLElement $list_xml)
    {
        $resource_list = [];

        foreach ($list_xml->source_info as $xml) {
            $resource = new Resource();
            $resource->internal_number = (string) $xml->source_internal_number;
            $resource->number = (string) $xml->source_001;
            $resource->name = (string) $xml->source_name;
            $resource->short_name = (string) $xml->source_short_name;
            $resource->searchable = ($xml->source_searchable_flag == 'Y');
            $resource_list[] = $resource;
        }
        return $resource_list;
    }
}