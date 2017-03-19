<?php

namespace NanoServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request(
            "POST",
            '/api/distance/from_1/to_2',
            [
                    '1' => '{"x":0,"y":0}',
                    '2' => '{"x":10,"y":10}',
                    '3' => '{"x":0,"y":10}'
            ]
        );

        
//        echo $client->getResponse()->getContent();
        //$this->assertContains('Wrong request method', $client->getResponse()->getContent());
    }
}
