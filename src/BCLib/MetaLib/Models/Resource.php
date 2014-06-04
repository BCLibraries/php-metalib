<?php

namespace BCLib\MetaLib\Models;

/**
 * Class Resource
 * @package BCLib\MetaLib\Models
 *
 * @property string internal_number
 * @property string number
 * @property string name
 * @property string short_name
 * @property string searchable
 */
class Resource
{
    protected $_internal_number;
    protected $_number;
    protected $_name;
    protected $_short_name;
    protected $_searchable;

    public function __toString()
    {
        return $this->name;
    }

    use Accessor;
    protected $_gettable = ['internal_number', 'number', 'name', 'short_name', 'searchable'];
    protected $_settable = ['internal_number', 'number', 'name', 'short_name', 'searchable'];
} 