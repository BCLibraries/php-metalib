<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Resource;

class BriefResourceReader implements ResponseReader
{
    /**
     * @param \SimpleXMLElement $list_xml
     * @return \BCLib\MetaLib\Resource[]
     */
    public function read(\SimpleXMLElement $list_xml)
    {
        $resource_list = [];

        foreach ($list_xml->xpath('//source_info') as $xml) {
            $resource = new Resource();
            $resource->internal_number = (string)$xml->source_internal_number;
            $resource->number = (string)$xml->source_001;
            $resource->name = (string)$xml->source_name;
            $resource->short_name = (string)$xml->source_short_name;
            $resource->searchable = ($xml->source_searchable_flag == 'Y');
            $resource_list[] = $resource;

            $xml->registerXPathNamespace('slim', 'http://www.loc.gov/MARC21/slim');
            $desc_fields = $xml->xpath('../slim:record/slim:datafield[@tag="520"]/slim:subfield[@code="a"]');
            if (isset($desc_fields[0])) {
                $resource->description = (string)$desc_fields[0];
            }
        }
        return $resource_list;
    }
}