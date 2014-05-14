<?php

namespace BCLib\MetaLib;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testIdLogsIn()
    {
        $username = 'foo';
        $passwd = 'bar';

        $params = [
            'user_name'     => $username,
            'user_password' => $passwd
        ];

        $response = $this->_loadFixture('valid-login-01.xml');

        $session_id = 'XHU95VLSX1NGLA6HQPVKTU5H8FDH6A65UT61QD36JB6F2VSJ47';

        $client = $this->getMockBuilder('\BCLib\MetaLib\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('send')
            ->with('login_request', $params, false)
            ->will($this->returnValue($response));

        $session = new Session($username, $passwd);
        $id = $session->id($client);
        $this->assertEquals($session_id, $id);
    }

    /**
     * @expectedException Exception
     */
    public function testBadLoginThrowsException()
    {
        $username = 'foo';
        $passwd = 'bar';

        $params = [
            'user_name'     => $username,
            'user_password' => $passwd
        ];

        $response = $this->_loadFixture('invalid-login-01.xml');

        $client = $this->getMockBuilder('\BCLib\MetaLib\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())
            ->method('send')
            ->will($this->returnValue($response));

        $session = new Session($username, $passwd);
        $id = $session->id($client);
    }

    public function testCacheGetsCheckedAndSet()
    {
        $session_id = 'XHU95VLSX1NGLA6HQPVKTU5H8FDH6A65UT61QD36JB6F2VSJ47';

        $cache = $this->getMockBuilder("\\Doctrine\\Common\\Cache\\Cache")
            ->disableOriginalConstructor()
            ->getMock();
        $cache->expects($this->once())
            ->method('contains')
            ->will($this->returnValue(true));
        $cache->expects($this->once())
            ->method('fetch');
        $cache->expects($this->once())
            ->method('save')
            ->with('metalib-cache-id', $session_id);

        $username = 'foo';
        $passwd = 'bar';

        $params = [
            'user_name'     => $username,
            'user_password' => $passwd
        ];

        $response = $this->_loadFixture('valid-login-01.xml');

        $client = $this->getMockBuilder('\BCLib\MetaLib\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())
            ->method('send')
            ->will($this->returnValue($response));

        $session = new Session($username, $passwd, $cache);
        $id = $session->id($client);
    }

    protected function _loadFixture($file)
    {
        return simplexml_load_file(__DIR__ . '/../../fixtures/' . $file);
    }

}
 