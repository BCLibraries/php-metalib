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

        $xml = <<<XML
<?xml version = "1.0" encoding = "UTF-8"?>
<x_server_response metalib_version="4.5.2 (790)">
    <login_response>
    <auth>Y</auth>
    <session_id new_session="Y">XHU95VLSX1NGLA6HQPVKTU5H8FDH6A65UT61QD36JB6F2VSJ47</session_id>
    </login_response>
</x_server_response>
XML;

        $session_id = 'XHU95VLSX1NGLA6HQPVKTU5H8FDH6A65UT61QD36JB6F2VSJ47';

        $response = new \SimpleXMLElement($xml);

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

        $xml = <<<XML
<?xml version = "1.0" encoding = "UTF-8"?>
<x_server_response metalib_version="4.5.2 (790)">
    <login_response>
    <auth>N</auth>
    </login_response>
</x_server_response>
XML;

        $response = new \SimpleXMLElement($xml);

        $client = $this->getMockBuilder('\BCLib\MetaLib\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())
            ->method('send')
            ->will($this->returnValue($response));

        $session = new Session($username, $passwd);
        $id = $session->id($client);
    }

}
 