<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Commands\GetCategories;
use BCLib\MetaLib\Commands\GetResourcesByCategory;
use BCLib\MetaLib\Models\Category;
use BCLib\MetaLib\Models\Resource;
use BCLib\MetaLib\Models\Subcategory;
use Doctrine\ORM\EntityManager;

class DataStoreLoader
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var Commands\GetCategories
     */
    protected $_get_cat;

    /**
     * @var Commands\GetResourcesByCategory
     */
    protected $_get_res;

    protected $_resource_hash = [];

    public function __construct(
        EntityManager $em,
        Client $client,
        GetCategories $get_cat,
        GetResourcesByCategory $get_res
    )
    {
        $this->_em = $em;
        $this->_client = $client;
        $this->_get_cat = $get_cat;
        $this->_get_res = $get_res;
        $this->_get_res->fullInfo();
    }

    public function load()
    {
        $categories = $this->_client->send($this->_get_cat);

        foreach ($categories as $category) {
            $this->_loadCategory($category);
        }

        $this->_em->flush();
    }

    protected function _loadCategory(Category $category)
    {
        foreach ($category->subcategories as $subcat) {
            $subcat->category = $category;
            $this->_loadResources($subcat);
            $this->_em->persist($subcat);
        }
        $this->_em->persist($category);
    }

    protected function _loadResources(Subcategory $subcategory)
    {
        $this->_get_res->setCategoryId($subcategory->sequence);
        $resources = $this->_client->send($this->_get_res);
        foreach ($resources as $resource) {
            $resource = $this->_getCanonicalResource($resource);
            $subcategory->resources[] = $resource;
        }
    }

    protected function _getCanonicalResource(Resource $res)
    {
        if (!isset($this->_resource_hash[$res->number])) {
            $this->_resource_hash[$res->number] = $res;
            $this->_em->persist($res);
        }
        return $this->_resource_hash[$res->number];
    }
}