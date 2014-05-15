<?php

namespace BCLib\MetaLib;

use Doctrine\Common\Cache\Cache;

class Services
{

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $_cache;
    private $_username;
    private $_passwd;

    /**
     * @var \BCLib\MetaLib\Client
     */
    private $_client;

    public function __construct($base_url, $username, $passwd, Cache $cache = null)
    {
        $this->_cache = $cache;
        $this->_username = $username;
        $this->_passwd = $passwd;
        $session = new Session($username, $passwd, $cache);
        $this->_client = new Client($base_url,$session, new \GuzzleHttp\Client());

    }

    public function getResourceServices($requester_ip = null, $institute = null)
    {
        return new ResourceService($this->_client, $requester_ip, $institute);
    }
} 