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
        return $this->_queries->sendResourcesQuery(null, $cache_ttl);
    }

    /**
     * @param $number    The resource number
     * @param $cache_ttl Query cache lifetime in seconds
     *
     * @return \BClib\MetaLib\Models\Resource[]
     */
    public function getResource($number, $cache_ttl)
    {
        return $this->_queries->sendResourcesQuery($number, $cache_ttl);
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


}