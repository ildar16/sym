<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users", methods={"GET"})
     */
    public function index(SerializerInterface $serializer)
    {
        $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
        
        $result = [];

        foreach ($users as $key => $user) {
            $articles = [];

            foreach ($user->getArticles() as $article) {
                $articles[] = [
                    'id' => $article->getId(),
                    'title' => $article->getTitle(),
                ];
            }

            $result[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'articles' => $articles
            ];
        }

        $json = $serializer->serialize($result, 'json', ['groups' => ['normal']]);

        return new Response($json, 200);
    }

    /**
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager)
    {
        $user = new User();
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));

        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "User added successfully",
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/users/{id}", name="users_get", methods={"GET"})
     */
    public function showUser(SerializerInterface $serializer, $id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user){
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return new JsonResponse($data, 404);
        }

        $articles = [];

        foreach ($user->getArticles() as $article) {
            $articles[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
            ];
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'articles' => $articles,
        ], 200);
    }

    /**
     * @Route("/users/{id}", name="users_put", methods={"PUT"})
     */
    public function updateUser(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $user = $userRepository->find($id);

        if (!$user){
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return new JsonResponse($data, 404);
        }

        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));

        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "User updated successfully",
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/users/{id}", name="users_delete", methods={"DELETE"})
     */
    public function deleteUser(UserRepository $userRepository, EntityManagerInterface $entityManager, $id)
    {
        $user = $userRepository->find($id);

        if (!$user){
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return new JsonResponse($data, 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "User deleted successfully",
        ];

        return new JsonResponse($data);
    }
}
