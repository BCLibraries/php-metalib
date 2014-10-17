<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Resource;

abstract class ResourceReader
{
    protected function readBriefInfo(\SimpleXMLElement $source_info, Resource $resource)
    {
        $resource->internal_number = (string) $source_info->source_internal_number;
        $resource->number = (string) $source_info->source_001;
        $resource->name = (string) $source_info->source_name;
        $resource->short_name = (string) $source_info->source_short_name;
        $resource->searchable = ($source_info->source_searchable_flag == 'Y');
    }

    abstract public function read(\SimpleXMLElement $list_xml);
} 