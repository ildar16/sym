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
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testAddUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/users', [
            'name' => 'test',
            'email' => 'test@test.ru',
        ]);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("User added successfully", $success);
    }

    public function testShowUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/10');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testUpdateUsers()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/users/5', [
            'name' => 'test1',
            'email' => 'test1@test.ru',
        ]);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("User updated successfully", $success);
    }

    public function testDeleteUser()
    {
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/users/5');

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("User deleted successfully", $success);
    }
}

