<?php

namespace NanoServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JSONAPIControllerTest extends WebTestCase
{
    public function testDistance()
    {
        //$client = static::createClient();

        static::createClient()->request('GET', '/api/json', ['t'=>'7']);
        //echo $client->getResponse()->getContent();

//        $client->request(
//            'POST',
//            '/api/json',
//            array(),
//            array(),
//            array('CONTENT_TYPE' => 'application/json'),
//            '{  "action":"distance",
//                "polygon": {
//                    "1": {"x":10,"y":10},
//                    "2": {"x":20,"y":20},
//                    "3": {"x":20,"y":10}
//                },
//                "from":"1",
//                "to":"2"}'
//        );


        //echo $client->getResponse()->getContent();
//        $this->assertContains('Wrong request method', $client->getResponse()->getContent());
    }
}
