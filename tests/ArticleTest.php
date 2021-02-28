<?php

namespace App\Tests;

use App\Entity\Aritcle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleTest extends WebTestCase
{

    private $entityManager;

    public function setUp() : void
    {
        $kernel = static::createKernel();

        $kernel->boot();
        $this->entityManager = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $article = new Aritcle();
        $article->setTitle('Unit test article');
        $article->setText('Unit test article');

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function getRandArticleId()
    {
        $articles = $this->entityManager->getRepository(Aritcle::class)->findAll();
        $randArticleId = $articles[array_rand($articles)]->getId();

        return $randArticleId;
    }

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

        $getRandArticleId = $this->getRandArticleId();
        $crawler = $client->request('GET', '/articles/' . $getRandArticleId);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testUpdateArticles()
    {
        $client = static::createClient();

        $getRandArticleId = $this->getRandArticleId();
        $crawler = $client->request('PUT', '/articles/' . $getRandArticleId, [
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

        $getRandArticleId = $this->getRandArticleId();
        $crawler = $client->request('DELETE', '/articles/' . $getRandArticleId);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Article deleted successfully", $success);
    }
}
