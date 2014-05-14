<?php

namespace BCLib\MetaLib;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $_session_id = 'example';

    public function testCheckForSessionIdWhenRequested()
    {
        $response = $this->_getResponse();
        $http_client = $this->_getHttpClient($response);

        $session = $this->getMockBuilder("\BCLib\MetaLib\Session")
            ->disableOriginalConstructor()
            ->getMock();
        $session->expects($this->once())
            ->method('id')
            ->will($this->returnValue($this->_session_id));

        $client = new Client('http://www.example.edu', $session, $http_client);
        $client->send('foo', []);
    }

    public function testDontCheckForSessionIdWhenNotRequested()
    {
        $response = $this->_getResponse();
        $http_client = $this->_getHttpClient($response);

        $session = $this->getMockBuilder("\BCLib\MetaLib\Session")
            ->disableOriginalConstructor()
            ->getMock();
        $session->expects($this->never())
            ->method('id')
            ->will($this->returnValue($this->_session_id));

        $client = new Client('http://www.example.edu', $session, $http_client);
        $client->send('foo', [], false);
    }

    public function testPassBackSimpleXMLOfResponse()
    {
        $response_xml = "<foo><bar></bar></foo>";

        $response = $this->_getResponse();
        $http_client = $this->_getHttpClient($response);
        $session = $this->_getSession();

        $client = new Client("http://www.example.edu", $session, $http_client);
        $response = $client->send('foo', []);
        $this->assertEquals($response, new \SimpleXMLElement($response_xml));
    }

    public function testBuildCorrectUrl()
    {
        $expected_url = 'http://www.example.edu/X?op=foo&key1=bar&key2=baz&session_id=' . $this->_session_id;

        $response = $this->_getResponse();
        $http_client = $this->_getHttpClient($response);
        $session = $this->_getSession();

        $http_client->expects($this->once())
            ->method('get')
            ->with($this->equalTo($expected_url));

        $client = new Client("http://www.example.edu", $session, $http_client);
        $client->send('foo', ['key1' => 'bar', 'key2' => 'baz']);
    }

    /**
     * @expectedException \BCLib\MetaLib\MetaLibException
     */
    public function testErrorResponseThrowsException()
    {
        $response_xml = file_get_contents(__DIR__ . '/../../fixtures/login-error-01.xml');

        $response = $this->getMock('\GuzzleHttp\Message\ResponseInterface');
        $response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($response_xml));

        $client = $this->_getHttpClient($response);
        $session = $this->_getSession();

        $client = new Client("http://www.example.edu", $session, $client);
        $client->send('foo', []);
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
        $http_client->expects($this->any())
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
}
 