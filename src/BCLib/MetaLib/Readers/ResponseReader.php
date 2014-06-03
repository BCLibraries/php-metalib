<?php

namespace BCLib\MetaLib\Readers;

interface ResponseReader
{
    public function read(\SimpleXMLElement $xml);
}
