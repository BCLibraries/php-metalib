<?php

namespace BCLib\MetaLib;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $_session_id = 'example';

    public function testBuildCorrectUrl()
    {
        $expected_url = 'http://www.example.edu/X?op=foo&key1=bar&key2=baz&session_id=' . $this->_session_id;

        $command = $this->getMockBuilder('\BCLib\MetaLib\Command')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $command->op = 'foo';
        $command->params = ['key1' => 'bar', 'key2' => 'baz', 'session_id' => $this->_session_id];
        $command->require_login = false;

        $http_client = $this->_getHttpClient($this->_getResponse());
        $http_client->expects($this->once())
            ->method('get')
            ->with($this->equalTo($expected_url));

        $login = $this->_getLogin();

        $client = new Client("http://www.example.edu", $http_client, $login);
        $client->send($command);
    }

    public function testClientCallsRead()
    {
        $http_client = $this->_getHttpClient($this->_getResponse());

        $xml = simplexml_load_string('<foo><bar></bar></foo>');

        $command = $this->getMockBuilder('\BCLib\MetaLib\Command')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $command->op = 'foo';
        $command->params = ['key1' => 'bar', 'key2' => 'baz', 'session_id' => $this->_session_id];
        $command->require_login = false;
        $command->expects($this->once())
            ->method('read')
            ->with($this->equalTo($xml));

        $login = $this->_getLogin();

        $client = new Client("http://www.example.edu", $http_client, $login);
        $client->send($command);
    }

    protected function _getResponse()
    {
        $response_xml = "<foo><bar></bar></foo>";

        $response = $this->getMock('\GuzzleHttp\Message\ResponseInterface');
        $response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($response_xml));
        return $response;
    }

    protected function _getHttpClient($response)
    {
        $http_client = $this->getMock('GuzzleHttp\Client');
        $http_client->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));
        return $http_client;
    }

    protected function _getSession()
    {
        $session = $this->getMockBuilder("\BCLib\MetaLib\Session")
            ->disableOriginalConstructor()
            ->getMock();
        $session->expects($this->any())
            ->method('id')
            ->will($this->returnValue($this->_session_id));
        return $session;
    }

    protected function _getLogin()
    {
        return $this->getMockBuilder('\BCLib\MetaLib\Commands\LoginCommand')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
 