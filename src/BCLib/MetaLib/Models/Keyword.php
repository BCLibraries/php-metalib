<?php

namespace BCLib\MetaLib\Models;

/**
 * Class Keyword
 * @package BCLib\MetaLib\Models
 *
 * @property string term
 */
class Keyword
{
    protected $_term;

    public function __toString()
    {
        return $this->term;
    }

    use Accessor;
    protected $_gettable = ['term'];
    protected $_settable = ['term'];
} 