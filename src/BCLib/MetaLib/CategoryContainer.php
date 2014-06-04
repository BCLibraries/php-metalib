<?php

namespace BCLib\MetaLib;

use BCLib\MetaLib\Models\Category;
use Traversable;

class CategoryContainer implements \IteratorAggregate
{

    /**
     * @var Models\Category[]
     */
    protected $_categories_by_num;

    /**
     * @var Models\Category[]
     */
    protected $_categories_by_name;

    public function sort()
    {
        usort(
            $this->_categories_by_num,
            function ($a, $b) {
                if ($a->name == $b->name) {
                    return 0;
                }

                if (strtolower($a->name) < strtolower($b->name)) {
                    return -1;
                }
                return 1;
            }
        );
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable
     */
    public function getIterator()
    {
        $this->sort();
        return new \ArrayIterator($this->_categories_by_num);
    }

    public function get($category_name, $subcategory_name = false)
    {
        $name_key = $this->_getNameKey($category_name);
        return isset($this->_categories_by_name[$name_key]) ? $this->_categories_by_name[$name_key] : null;
    }

    public function add(Category $category)
    {
        $this->_categories_by_num[] = $category;

        $name_key = $this->_getNameKey($category->name);
        $this->_categories_by_name[$name_key] = $category;
    }

    protected function _getNameKey($category_name)
    {
        return urlencode(strtolower($category_name));
    }
}