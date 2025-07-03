<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment; 
use App\Form\CommentType; 
use App\Repository\PostRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'post_show')]
    public function show(Post $post, Request $request, EntityManagerInterface $em): Response
{
    $comment = new Comment();
    $comment->setPost($post);
    $comment->setAuthor($this->getUser());
    $comment->setCreatedAt(new \DateTimeImmutable());
    $comment->setUpdatedAt(new \DateTimeImmutable());
    $comment->setIsApproved(false); 

    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $comment->setUpdatedBy($this->getUser());

        $em->persist($comment);
        $em->flush();
        $this->addFlash('success', 'Votre commentaire a été soumis pour validation.');
        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }

    return $this->render('post/post.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
    }
    #[Route('/articles', name: 'post_list')]
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/comment/{id}/report', name: 'comment_report')]
    public function report(Comment $comment, EntityManagerInterface $em): Response
    {
        $comment->setIsApproved(false); // repasse le commentaire en attente de modération
        $em->flush();

        $this->addFlash('success', 'Le commentaire a été signalé et sera vérifié par un modérateur.');

        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }


}
