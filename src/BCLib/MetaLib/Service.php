<?php

namespace BCLib\MetaLib;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class Service
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var \BCLib\MetaLib\Client
     */
    protected $_client;

    public function __construct(Client $client, EntityManager $em)
    {
        $this->_em = $em;
        $this->_client = $client;
    }

    /**
     * @param int $cache_ttl Query cache lifetime in seconds
     *
     * @return \BClib\MetaLib\Models\Resource[]
     */
    public function getAllResources($cache_ttl = 3600)
    {
        return $this->_sendResourcesQuery(null, $cache_ttl);
    }

    /**
     * @param $number    The resource number
     * @param $cache_ttl Query cache lifetime in seconds
     *
     * @return \BClib\MetaLib\Models\Resource[]
     */
    public function getResource($number, $cache_ttl)
    {
        return $this->_sendResourcesQuery($number, $cache_ttl);
    }

    /**
     * @param int $cache_ttl
     *
     * @return \BCLib\MetaLib\Models\Category[]
     */
    public function getAllCategories($cache_ttl = 3600)
    {
        return $this->_sendCategoriesQuery(null, $cache_ttl);
    }

    /**
     * @param     $id
     * @param int $cache_ttl
     *
     * @return \BCLib\MetaLib\Models\Category[]
     */
    public function getCategory($id, $cache_ttl = 3600)
    {
        return $this->_sendCategoriesQuery($id, $cache_ttl);
    }

    protected function _setQueryCache(Query $query, $ttl)
    {
        if (!is_null($this->_em->getConfiguration()->getQueryCacheImpl())) {
            $query->useResultCache(true, $ttl);
        }
        return $query;
    }

    protected function _sendCategoriesQuery($id = null, $ttl = 3600)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Category', 'Subcategory', 'Resource')
            ->from('\BCLib\MetaLib\Models\Category', 'Category')
            ->leftJoin('Category._subcategories', 'Subcategory')
            ->leftJoin('Subcategory._resources', 'Resource')
            ->orderBy('Category._name', 'ASC')
            ->addOrderBy('Subcategory._name', 'ASC')
            ->addOrderBy('Resource._name', 'ASC');
        if (!is_null($id)) {
            $qb->where($qb->expr()->eq('Category._name', ':id'))
                ->setParameter('id', $id);
        }
        return $this->_sendQuery($qb, $ttl);
    }

    protected function _sendResourcesQuery($id = null, $ttl = 3600)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Resource')
            ->from('\BCLib\MetaLib\Models\Category', 'Category')
            ->orderBy('Resource._name', 'ASC');
        if (!is_null($id)) {
            $qb->where($qb->expr()->eq('Category._number', ':id'))
                ->setParameter(':id', $id);
        }
        return $this->_sendQuery($qb, $ttl);
    }

    protected function _sendQuery(QueryBuilder $qb, $ttl)
    {
        $query = $qb->getQuery();
        $query = $this->_setQueryCache($query, $ttl);
        return $query->getResult();
    }
}