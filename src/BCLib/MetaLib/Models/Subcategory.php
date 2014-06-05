<?php

namespace BCLib\MetaLib\Models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Subcategory
 * @package BCLib\MetaLib\Models
 *
 * @property string     name
 * @property string     sequence
 * @property string     bases
 * @property Category   category
 * @property array      resources
 */
class Subcategory
{
    protected $_name;
    protected $_sequence;
    protected $_bases;

    /**
     * @var Category
     */
    protected $_category;

    /**
     * @var Resource[]
     */
    protected $_resources;

    /**
     * Add access to attributes.
     */
    use Accessor;
    protected $_gettable = ['name', 'sequence', 'bases', 'category', 'resources'];
    protected $_settable = ['name', 'sequence', 'bases', 'category', 'resources'];

    public function __construct()
    {
        $this->_resources = new ArrayCollection();
    }
} 