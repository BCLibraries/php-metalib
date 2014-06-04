<?php

namespace BCLib\MetaLib\Models;

/**
 * Class QuickSet
 * @package BCLib\MetaLib\Models
 *
 * @property string name
 * @property string sequence
 * @property string bases
 * @property string description
 */
class QuickSet
{
    protected $_name;
    protected $_sequence;
    protected $_bases;
    protected $_description;

    use Accessor;
    protected $_gettable = ['name', 'sequence', 'bases', 'descriptions'];
    protected $_settable = ['name', 'sequence', 'bases', 'descriptions'];
}