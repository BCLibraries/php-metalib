<?php

namespace BCLib\MetaLib;

use Doctrine\ORM\Query;

class Service
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    /**
     * @var \BCLib\MetaLib\Client
     */
    protected $_client;

    /**
     * @var QueryRepository
     */
    protected $_queries;

    public function __construct(Client $client, QueryRepository $queries)
    {
        $this->_queries = $queries;
        $this->_client = $client;
    }

    /**
     * @param int $cache_ttl Query cache lifetime in seconds
     *
     * @return \BClib\MetaLib\Models\Resource[]
     */
    public function getAllResources($cache_ttl = 3600)
    {
        return $this->_queries->sendResourcesQuery([], $cache_ttl);
    }

    /**
     * @param $number    string resource number
     * @param $cache_ttl int cache lifetime in seconds
     *
     * @return \BClib\MetaLib\Models\Resource[]
     */
    public function getResource($number, $cache_ttl = 3600)
    {
        return $this->_queries->sendResourcesQuery(['id' => $number], $cache_ttl);
    }

    /**
     * @param int $cache_ttl
     *
     * @return \BCLib\MetaLib\Models\Category[]
     */
    public function getAllCategories($cache_ttl = 3600)
    {
        return $this->_queries->sendCategoriesQuery(null, $cache_ttl);
    }

    /**
     * @param     $id
     * @param int $cache_ttl
     *
     * @return \BCLib\MetaLib\Models\Category[]
     */
    public function getCategory($id, $cache_ttl = 3600)
    {
        return $this->_queries->sendCategoriesQuery($id, $cache_ttl);
    }

    /**
     * @param string $first_letters
     * @param int    $cache_ttl
     *
     * @return Resource[]s
     * @throws MetaLibException
     */
    public function getResourcesByFirstLetters($first_letters, $cache_ttl = 3600)
    {
        if (!is_string($first_letters) || strlen($first_letters) < 1) {
            throw new MetaLibException("$first_letters is not a valid first letter(s)");
        }
        return $this->_queries->sendResourcesQuery(['first_letter' => $first_letters], $cache_ttl);
    }

    /**
     * @param string $name
     * @param int    $cache_ttl
     *
     * @return Resource[]
     * @throws MetaLibException
     */
    public function getResourcesByName($name, $cache_ttl = 3600)
    {
        if (!is_string($name)) {
            throw new MetaLibException("$name is not a valid resource name");
        }
        return $this->_queries->sendResourcesQuery(['name' => $name], $cache_ttl);
    }

}