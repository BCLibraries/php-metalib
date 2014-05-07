<?php

namespace BCLib\MetaLib;

class Session
{
    protected $_id;
    private $_user_name;
    private $_password;

    public function __construct($user_name, $password)
    {
        $this->_id = false;
        $this->_user_name = $user_name;
        $this->_password = $password;
    }

    public function id(Client $client)
    {
        if (!$this->_id) {
            $this->_login($client);
        }
        return $this->_id;
    }

    protected function _login(Client $client)
    {
        $op = 'login_request';
        $param = [
            'user_name' => $this->_user_name,
            'user_password'  => $this->_password
        ];
        $xml = $client->send($op, $param, false);
        if ($xml->login_response->auth == 'Y') {
            $this->_id = (string) $xml->login_response->session_id;
        } else {
            throw new \Exception("Invalid login");
        }
    }
}