<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Commands\LoginCommand;
use Doctrine\Common\Cache\Cache;

class Client
{
    private $_base_url;

    /**
     * @var \GuzzleHttp\Client
     */
    private $_http_client;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $_cache;

    /**
     * @var Commands\LoginCommand
     */
    private $_login;

    private $_session_id;

    const SESSION_CACHE_KEY = 'metalib-session-id';

    const ERR_NOT_AUTHORIZED_CODE = '0151';
    const ERR_SESSION_TIMEOUT = '2050';

    public function __construct($base_url, \GuzzleHttp\Client $http_client, LoginCommand $login, Cache $cache = null)
    {
        $this->_http_client = $http_client;
        $this->_base_url = $base_url;
        $this->_cache = $cache;
        $this->_login = $login;
    }

    public function send(Command $command)
    {
        $url = $this->_buildURL($command);

        $result = $this->_http_client->get($url);

        $xml = new \SimpleXMLElement($result->getBody());

        $error_elements = $xml->xpath('//error_text');
        if (count($error_elements) > 0) {
            $code = (string) $xml->xpath('//error_code')[0];
            $xml = $this->_handleError($command, $code, $error_elements, $url);
        }

        return $command->read($xml);
    }

    protected function _handleError(Command $command, $code, $message, $url)
    {
        if (self::ERR_NOT_AUTHORIZED_CODE == $code || self::ERR_SESSION_TIMEOUT == $code) {
            $this->login();
            return $this->send($command);
        } else {
            return $command->notify($code, $message, $url);
        }
    }

    protected function _buildURL(Command $command)
    {
        $url = $this->_base_url . "/X?op=" . $command->op;
        $url .= '&' . \http_build_query($command->params);
        if ($command->require_login) {
            $url .= '&session_id=' . $this->id($this);
            return $url;
        }
        return $url;
    }

    public function id(Client $client)
    {
        if ($this->_session_id) {
            // Do nothing if we already have a session id.
        } elseif ($this->_cache->contains(self::SESSION_CACHE_KEY)) {
            $this->_session_id = $this->_cache->fetch(self::SESSION_CACHE_KEY);
        } else {
            $this->login();
        }
        return $this->_session_id;
    }

    public function login()
    {
        $this->_session_id = $this->send($this->_login);
        $this->_cache->save(self::SESSION_CACHE_KEY, $this->_session_id);
    }
}