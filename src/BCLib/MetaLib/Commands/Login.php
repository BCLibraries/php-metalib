<?php

namespace BCLib\MetaLib\Commands;

use BCLib\MetaLib\Command;

class Login extends Command
{
    public function __construct($user, $password, $ip = '127.0.0.1')
    {
        $op = 'login_request';
        $params = [
            'user_name'     => $user,
            'user_password' => $password,
            'requester_ip'  => $ip
        ];
        $require_login = false;
        parent::__construct($op, $params, $require_login);
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return string the session ID
     * @throws \Exception
     */
    public function read(\SimpleXMLElement $xml)
    {
        if ($xml->login_response->auth != 'Y') {
            throw new \Exception("Invalid login");
        }
        return (string) $xml->login_response->session_id;
    }
}