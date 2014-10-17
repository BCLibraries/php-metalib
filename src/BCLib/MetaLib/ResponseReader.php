<?php

namespace BCLib\MetaLib;

interface ResponseReader
{
    public function read(\SimpleXMLElement $list_xml);
} 