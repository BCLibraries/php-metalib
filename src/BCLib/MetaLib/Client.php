<?php

namespace BCLib\MetaLib;

class Client
{

    private $_base_url;

    /**
     * @var \GuzzleHttp\Client
     */
    private $_http_client;

    /**
     * @var Session
     */
    private $_session;

    public function __construct($base_url, Session $session, \GuzzleHttp\Client $http_client)
    {
        $this->_http_client = $http_client;
        $this->_base_url = $base_url;
        $this->_session = $session;
    }

    public function send($op, array $params, $require_login = true)
    {
        $url = $this->_base_url . "/X?op=" . $op;
        $url .= '&' . \http_build_query($params);
        if ($require_login) {
            $url .= '&session_id=' . $this->_session->id($this);
        }
        $result = $this->_http_client->get($url);

        $xml = new \SimpleXMLElement($result->getBody());
        $this->_checkErrors($xml);
        return $xml;
    }

    protected function _checkErrors(\SimpleXMLElement $xml)
    {
        $error_elements = $xml->xpath('//error_text');
        if (count($error_elements) > 0) {

            $code = (string) $xml->xpath('//error_code')[0];
            if ($code != '0151') {
                throw new MetaLibException((string) $error_elements[0]);
            }
        }
    }
}