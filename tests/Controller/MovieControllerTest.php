<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testSearch(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movie/search');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Searching movie');
        
        // When keyword is given
        $client->request('GET', '/movie/search?keyword=Matrix');
        $this->assertCount(10, $client->getCrawler()->filter('table tbody tr'));
        
        // When no keyword given
        $client->request('GET', '/movie/search');
        $this->assertEquals('Searching movie "Sky".', $client->getCrawler()->filter('h1')->text());
        
        // When search is too broad
        $client->request('GET', '/movie/search?keyword=a');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }
}
