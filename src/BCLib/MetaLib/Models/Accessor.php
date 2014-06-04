<?php

namespace BCLib\MetaLib\Models;

trait Accessor
{
    public function __get($name)
    {
        if (isset($this->_gettable) && in_array($name, $this->_gettable)) {
            $name = "_$name";
            return $this->$name;
        }

        $name = ucfirst($name);
        $method_name = "_get" . preg_replace_callback(
                '/_(.)/',
                function ($c) {
                    return strtoupper($c[1]);
                },
                $name
            );

        if (method_exists($this, $method_name)) {
            return $this->$method_name();
        }

        throw new \Exception("$name is not a gettable attribute");
    }

    public function __set($name, $value)
    {
        if (isset($this->_settable) && in_array($name, $this->_settable)) {
            $name = "_$name";
            $this->$name = $value;
        } else {
            $method_name = "_set" . preg_replace_callback(
                    '/(^.)|_(.)/',
                    function ($c) {
                        return strtoupper($c[1]);
                    },
                    $name
                );
            if (method_exists($this, $method_name)) {
                $this->$method_name($value);
            } else {
                throw new \Exception("$name is not a settable attribute");
            }
        }
    }
}