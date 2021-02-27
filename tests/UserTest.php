<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testUsers()
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/users', [
            'name' => 'test',
            'email' => 'test@test.ru',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/5');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/users/5', [
            'name' => 'test1',
            'email' => 'test1@test.ru',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUser()
    {
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/users/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

