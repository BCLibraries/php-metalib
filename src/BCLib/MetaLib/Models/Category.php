<?php

namespace BCLib\MetaLib\Models;

/**
 * Class Category
 * @package BCLib\MetaLib
 *
 * @property string        name
 * @property Subcategory[] subcategories
 */
class Category
{
    protected $_name;

    /**
     * @var \BCLib\MetaLib\Subcategory[]
     */
    protected $_subcategories;

    /**
     * Add access to attributes
     */
    use Accessor;
    protected $_gettable = ['name', 'subcategories'];
    protected $_settable = ['name', 'subcategories'];

    public function __construct()
    {
        $this->_subcategories = new \ArrayObject();
    }
}