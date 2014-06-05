<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Commands\GetCategories;
use BCLib\MetaLib\Commands\GetResourcesByCategory;
use BCLib\MetaLib\Commands\LoginCommand;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use GuzzleHttp\Client as GuzzleClient;

class MetaLib
{
    protected $_base_url;

    /**
     * @var LoginCommand
     */
    protected $_login;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    protected $_db_conn_params = [];

    protected $_is_dev_mode = false;

    const DB_TYPE_MYSQL = 'pdo_mysql';

    public function accessParams($metadata_host, $user, $passwd, $ip = null)
    {
        $this->_base_url = $metadata_host;
        $this->_login = new LoginCommand($user, $passwd, $ip);
        return $this;
    }

    public function developmentMode($is_dev_mode)
    {
        $this->_is_dev_mode = $is_dev_mode;
        return $this;
    }

    public function dbParams($host, $name, $user, $passwd, $type = self::DB_TYPE_MYSQL)
    {
        $this->_db_conn_params = [
            'dbname'   => $name,
            'user'     => $user,
            'password' => $passwd,
            'host'     => $host,
            'driver'   => $type,
        ];

        return $this;
    }

    public function cache(Cache $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    public function getService()
    {
        $this->_checkServiceRequirements();
        $client = $this->_buildClient();
        $em = $this->_buildEntityManager();
        $queries = new QueryRepository($em);
        return new Service($client, $queries);
    }


    public function getLoader($requester_ip)
    {
        $this->_checkServiceRequirements();
        $client = $this->_buildClient();
        $em = $this->_buildEntityManager();
        $get_cat = new GetCategories($requester_ip);
        $get_res = new GetResourcesByCategory();
        return new DataStoreLoader($em, $client, $get_cat, $get_res);
    }

    protected function _checkServiceRequirements()
    {
        if (!isset($this->_login)) {
            throw new MetaLibException("Must set access information before connecting");
        }

        if (empty($this->_db_conn_params)) {
            throw new MetaLibException("Must set database connection information before connecting.");
        }

        if (!isset($this->_cache)) {
            $this->_cache = new NullCache();
        }
    }

    protected function _buildEntityManager()
    {
        $config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/../../../config/yaml"), $this->_is_dev_mode);
        $config->setMetadataCacheImpl($this->_cache);
        $config->setResultCacheImpl($this->_cache);
        $config->setQueryCacheImpl($this->_cache);

        return EntityManager::create($this->_db_conn_params, $config);
    }

    protected function _buildClient()
    {
        $guzzle = new GuzzleClient();
        return new Client($this->_base_url, $guzzle, $this->_login, $this->_cache);
    }
}