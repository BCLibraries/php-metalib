<?php

namespace BCLib\MetaLib;

/**
 * Class Command
 * @package BCLib\MetaLib
 *
 * @property-read string         $op
 * @property-read string[]       $params
 * @property-read boolean        require_login
 */
abstract class Command
{
    public $_op;
    public $_params;
    public $_require_login;

    /**
     * @var callable[]
     */
    protected $_errorListeners;

    public function __construct($op, $params, $require_login)
    {
        $this->_op = $op;
        $this->_params = $params;
        $this->_require_login = $require_login;
    }

    public function addErrorListener($error_code, callable $callback)
    {
        $this->_errorListeners[$error_code] = $callback;
    }

    public function notify($error_code, $message, $url)
    {
        if (!isset($this->_errorListeners[$error_code])) {
            throw new MetaLibException("MetaLib Exception ($error_code) $message <$url>");
        }
        return $this->_errorListeners[$error_code]($message, $url);
    }

    public function __get($name)
    {
        $accessible_attributes = ['op', 'params', 'require_login'];
        if (in_array($name, $accessible_attributes)) {
            $name = "_$name";
            return $this->$name;
        }
    }

    abstract public function read(\SimpleXMLElement $response_xml);
}