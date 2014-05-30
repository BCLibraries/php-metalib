<?php

namespace BCLib\MetaLib;

interface Cacheable
{
    public function saveCategory(Category $category);
    public function fetchCategory($category_name);
    public function saveAllResources(array $all_resources);
    public function fetchAllResources();
    public function saveAllCategories(CategoryContainer $all_categories);
    public function fetchAllCategories();
}
