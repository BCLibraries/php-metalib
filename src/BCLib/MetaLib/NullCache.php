<?php

namespace BCLib\MetaLib;

class NullCache implements Cacheable
{

    public function saveCategory(Category $category)
    {
        // NOOP
    }

    public function fetchCategory($category_name)
    {
        return null;
    }

    public function saveAllResources(array $all_resources)
    {
        // NOOP
    }

    public function fetchAllResources()
    {
        return null;
    }

    public function saveAllCategories(array $all_categories)
    {
        // NOOP
    }

    public function fetchAllCategories()
    {
        return null;
    }
}