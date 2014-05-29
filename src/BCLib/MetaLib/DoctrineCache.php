<?php

namespace BCLib\MetaLib;

use Doctrine\Common\Cache\ApcCache;

class DoctrineCache implements Cacheable
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    protected $_prefix;

    protected $_all_categories_key;
    protected $_category_key_prefix;
    protected $_all_resources_key;

    protected $_all_categories_ttl = -1;
    protected $_category_ttl = -1;
    protected $_all_dbs_ttl = -1;

    public function __construct(\Doctrine\Common\Cache\Cache $cache, $prefix = 'php-metalib')
    {
        $this->_cache = $cache;
        $this->_all_categories_key = $prefix . '-all-categories';
        $this->_category_key_prefix = $prefix . '-category-';
        $this->_all_resources_key = $prefix . '-all-resources';
    }

    public function saveCategory(Category $category)
    {
        $this->_cache->save($this->_categoryKey($category->name), $category, $this->_category_ttl);
    }

    public function fetchCategory($category_name)
    {
        $this->_fetch($this->_categoryKey($category_name));
    }

    public function saveAllResources(array $all_resources)
    {
        $this->_cache->save($this->_all_resources_key, $all_resources, $this->_all_dbs_ttl);
    }

    public function fetchAllResources()
    {
        return $this->_fetch($this->_all_resources_key);
    }

    public function saveAllCategories(array $all_categories)
    {
        $this->_cache->save($this->_all_categories_key, $all_categories, $this->_all_categories_ttl);
    }

    public function fetchAllCategories()
    {
        return $this->_fetch($this->_all_categories_key);
    }

    protected function _fetch($key)
    {
        if ($this->_cache->contains($key)) {
            return $this->_cache->fetch($key);
        } else {
            return null;
        }
    }

    protected function _categoryKey($category_name)
    {
        return $this->_category_key_prefix . $category_name;
    }
}