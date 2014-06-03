<?php

namespace BCLib\MetaLib;

class ResourceListReader implements ResponseReader
{
    /**
     * @param \SimpleXMLElement $xml
     *
     * @return Resource[]
     */
    public function read(\SimpleXMLElement $xml)
    {
        $list_xml = $xml->children()[0];

        if ($list_xml->getName() == 'retrieve_resources_by_quick_set_response') {
            $list_xml = $list_xml->set_info;
        }

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