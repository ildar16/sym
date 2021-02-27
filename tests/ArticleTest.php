<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleTest extends WebTestCase
{
    public function testArticles()
    {
        $client = static::createClient();

        $client->request('GET', '/articles');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/articles', [
            'title' => 'test',
            'text' => 'test test',
            'user_id' => '5',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles/6');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/articles/6', [
            'title' => 'test1',
            'text' => 'test1 test',
            'user_id' => '6',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/articles/10');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
