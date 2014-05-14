<?php

namespace BCLib\MetaLib;

use Doctrine\Common\Cache\Cache;

class Session
{
    protected $_id = false;
    private $_user_name;
    private $_password;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $_cache;

    const CACHE_KEY = 'metalib-cache-id';

    public function __construct($user_name, $password, Cache $cache = null)
    {
        $this->_user_name = $user_name;
        $this->_password = $password;
        $this->_cache = $cache;
    }

    public function id(Client $client)
    {
        $this->_checkCache();
        if (!$this->_id) {
            $this->login($client);
        }
        return $this->_id;
    }

    public function login(Client $client)
    {
        $op = 'login_request';
        $param = [
            'user_name'     => $this->_user_name,
            'user_password' => $this->_password
        ];
        $xml = $client->send($op, $param, false);
        if ($xml->login_response->auth != 'Y') {
            throw new \Exception("Invalid login");
        }
        $this->_id = (string) $xml->login_response->session_id;
        $this->_cacheId($this->_id);
    }

    protected function _cacheId($id)
    {
        if (isset($this->_cache)) {
            $this->_cache->save(self::CACHE_KEY, $id);
        }
    }

    protected function _checkCache()
    {
        if (isset($this->_cache) && $this->_cache->contains(self::CACHE_KEY)) {
            $this->_id = $this->_cache->fetch(self::CACHE_KEY);
        }
    }
}