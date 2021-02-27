<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Aritcle;
use App\Entity\User;
use App\Repository\AritcleRepository;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles", methods={"GET"})
     */
    public function index(SerializerInterface $serializer)
    {
        $article = $this->getDoctrine()
            ->getRepository(Aritcle::class)
            ->findAll();

        return $this->json($article, Response::HTTP_OK, [], ['groups' => 'articles']);
    }

    /**
     * @Route("/articles", name="articles_add", methods={"POST"})
     */
    public function addArticle(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Aritcle();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->get('user_id'));

        $article->setTitle($request->get('title'));
        $article->setUser($user);
        $article->setText($request->get('text'));

        $entityManager->persist($article);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "Article added successfully",
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/articles/{id}", name="articles_get", methods={"GET"})
     */
    public function showArticle(SerializerInterface $serializer, $id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Aritcle::class)
            ->find($id);

        if (!$article){
            $data = [
                'status' => 404,
                'errors' => "Article not found",
            ];
            return new JsonResponse($data, 404);
        }

        return new JsonResponse([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'text' => $article->getText(),
        ]);
    }

    /**
     * @Route("/articles/{id}", name="articles_put", methods={"PUT"})
     */
    public function updateArticle(AritcleRepository $aritcleRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $article = $aritcleRepository->find($id);

        if (!$article){
            $data = [
                'status' => 404,
                'errors' => "Article not found",
            ];
            return new JsonResponse($data, 404);
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->get('user_id'));

        $article->setTitle($request->get('title'));
        $article->setText($request->get('text'));
        $article->setUser($user);

        $entityManager->persist($article);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "Article updated successfully",
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/articles/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function deleteArticle(AritcleRepository $aritcleRepository, EntityManagerInterface $entityManager, $id)
    {
        $article = $aritcleRepository->find($id);

        if (!$article){
            $data = [
                'status' => 404,
                'errors' => "Article not found",
            ];
            return new JsonResponse($data, 404);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'success' => "Article deleted successfully",
        ];

        return new JsonResponse($data);
    }
}

