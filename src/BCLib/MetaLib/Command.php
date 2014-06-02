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
class Command
{
    protected $_op;
    protected $_params;
    protected $_require_login;

    /**
     * @var ResponseReader
     */
    protected $_response_reader;

    /**
     * @var callable[]
     */
    protected $_errorListeners;

    public function __construct($op, $params, $require_login, ResponseReader $response_reader)
    {
        $this->op = $op;
        $this->params = $params;
        $this->_require_login = $require_login;
        $this->_response_reader = $response_reader;
        $this->_errorListeners = [];
    }

    public function addErrorListener($error_code, callable $callback)
    {
        $this->_errorListeners[$error_code] = $callback;
    }

    public function notify($error_code, $message, $url)
    {
        if (!isset($this->_errorListeners[$error_code])) {
            throw new MetaLibException("MetaLib Exception ($error_code) $mesage <$url>");
        }

        return $this->_errorListeners[$error_code]($message, $url);
    }

    public function read(\SimpleXMLElement $_response_xml)
    {
        return $this->_response_reader->read($_response_xml);
    }

    public function __get($name)
    {
        $accessible_attributes = ['op', 'params', 'require_login'];
        if (in_array($name, $accessible_attributes)) {
            $name = "_$name";
            return $this->$name;
        }
    }
}