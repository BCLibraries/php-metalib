<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Resource;

class BriefResourceReader extends ResourceReader
{
    /**
     * @param \SimpleXMLElement $list_xml
     * @return \BCLib\MetaLib\Resource[]
     */
    public function read(\SimpleXMLElement $list_xml)
    {
        $resource_list = [];

        foreach ($list_xml->xpath('//source_info') as $source_info) {
            $resource_list[] = $this->readSourceInfo($source_info, new Resource());
        }
        return $resource_list;
    }
}