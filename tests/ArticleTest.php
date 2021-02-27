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
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testAddArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/articles', [
            'title' => 'test',
            'text' => 'test test',
            'user_id' => '5',
        ]);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Article added successfully", $success);
    }

    public function testShowArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles/32');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testUpdateArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/articles/32', [
            'title' => 'test1',
            'text' => 'test1 test',
            'user_id' => '6',
        ]);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Article updated successfully", $success);
    }

    public function testDeleteArticles()
    {
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/articles/6');

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Article deleted successfully", $success);
    }
}
