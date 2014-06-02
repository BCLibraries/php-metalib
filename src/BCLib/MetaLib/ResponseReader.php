<?php

namespace BCLib\MetaLib;

interface ResponseReader
{
    public function read(\SimpleXMLElement $response_xml);
}
