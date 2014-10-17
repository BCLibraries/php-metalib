<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Keyword;
use BCLib\MetaLib\Models\Resource;

class FullResourceReader extends ResourceReader
{
    /**
     * @param \SimpleXMLElement $list_xml
     * @return \BCLib\MetaLib\Resource[]
     */
    public function read(\SimpleXMLElement $list_xml)
    {
        $resource_list = [];

        foreach ($list_xml->xpath('//source_info') as $source_info) {
            $resource = $this->readSourceInfo($source_info, new Resource());

            $source_info->registerXPathNamespace('slim', 'http://www.loc.gov/MARC21/slim');

            $desc_fields = $source_info->xpath(
                '../slim:record/slim:datafield[@tag="520"]/slim:subfield[@code="a"]'
            );

            if (isset($desc_fields[0])) {
                $resource->description = (string) $desc_fields[0];
            }

            foreach ($source_info->xpath(
                '../slim:record/slim:datafield[@tag="653"]/slim:subfield[@code="a"]'
            ) as $keyword_field) {
                $keyword = new Keyword();
                $keyword->term = (string) $keyword_field;
                $resource->addKeyword($keyword);
            }
            $resource_list[] = $resource;
        }
        return $resource_list;
    }
}