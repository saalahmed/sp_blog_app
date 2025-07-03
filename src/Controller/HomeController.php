<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            3
        );

        return $this->render('home/home.html.twig', [
            'posts' => $posts, // 
        ]);
    }
}

