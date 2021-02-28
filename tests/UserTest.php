<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
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

        $user = new User();
        $user->setName('Unit test user');
        $user->setEmail('Unit test user');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getRandUserId()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $randUserId = $users[array_rand($users)]->getId();

        return $randUserId;
    }

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

        $randUserId = $this->getRandUserId();
        $crawler = $client->request('GET', '/users/' . $randUserId);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent(), 200);
    }

    public function testUpdateUsers()
    {
        $client = static::createClient();

        $randUserId = $this->getRandUserId();
        $crawler = $client->request('PUT', '/users/' . $randUserId, [
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

        $randUserId = $this->getRandUserId();
        $crawler = $client->request('DELETE', '/users/' . $randUserId);

        $response = $client->getResponse();
        $success = json_decode($response->getContent())->success;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("User deleted successfully", $success);
    }
}

