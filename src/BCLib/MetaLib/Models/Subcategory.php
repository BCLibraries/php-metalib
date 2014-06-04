<?php

namespace BCLib\MetaLib\Models;

/**
 * Class Subcategory
 * @package BCLib\MetaLib\Models
 *
 * @property string name
 * @property string sequence
 * @property string bases
 */
class Subcategory
{
    protected $_name;
    protected $_sequence;
    protected $_bases;

    /**
     * Add access to attributes.
     */
    use Accessor;
    protected $_gettable = ['name', 'sequence', 'bases'];
    protected $_settable = ['name', 'sequence', 'bases'];
} 