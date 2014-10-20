<?php

namespace BCLib\MetaLib\Models;

/**
 * Class Resource
 * @package BCLib\MetaLib\Models
 *
 * @property string                          internal_number
 * @property string                          number
 * @property string                          name
 * @property string                          short_name
 * @property string                          searchable
 * @property string                          description
 * @property \BCLib\Metalib\Models\Keyword[] keywords
 */
class Resource
{
    protected $_internal_number;
    protected $_number;
    protected $_name;
    protected $_short_name;
    protected $_searchable;
    protected $_description;
    protected $_keywords = [];
    protected $_keywords_by_term = [];

    public function __toString()
    {
        return $this->name;
    }

    use Accessor;
    protected $_gettable = ['internal_number', 'number', 'name', 'short_name', 'searchable', 'description', 'keywords'];
    protected $_settable = ['internal_number', 'number', 'name', 'short_name', 'searchable', 'description', 'keywords'];

    public function addKeyword(Keyword $keyword)
    {
        if (!isset($this->_keywords_by_term[$keyword->term])) {
            $this->_keywords[] = $keyword;
            $this->_keywords_by_term[$keyword->term] = true;
        }
    }
} 