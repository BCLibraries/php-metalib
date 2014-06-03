<?php
/**
 * Created by PhpStorm.
 * User: florinb
 * Date: 6/3/14
 * Time: 10:50 AM
 */

namespace BCLib\MetaLib\Readers;

class LoginReader implements ResponseReader
{
    public function read(\SimpleXMLElement $xml)
    {
        if ($xml->login_response->auth != 'Y') {
            throw new \Exception("Invalid login");
        }
        return (string) $xml->login_response->session_id;
    }
}