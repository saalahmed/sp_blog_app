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
use Knp\Component\Pager\PaginatorInterface;
class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'post_show')]
    public function show(int $id, Request $request, EntityManagerInterface $em, PostRepository $postRepository): Response
    {
        $post = $postRepository->findWithApprovedComments($id);

        if (!$post) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($this->getUser());
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $comment->setIsApproved(false);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());

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


    #[Route('/posts', name: 'post_list')]
    public function list(PaginatorInterface $paginator, Request $request, PostRepository $postRepository): Response
    {
        $queryText = $request->query->get('query', '');

        $qb = $postRepository->createQueryBuilder('p')
            ->where('p.isPublished = :published')
            ->andWhere('p.postAt <= :now')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('p.postAt', 'DESC');

        if ($queryText) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search')
                ->setParameter('search', '%' . $queryText . '%');
        }

        $query = $qb->getQuery();

        $paginatedPosts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        foreach ($paginatedPosts as $post) {
            // force lazy loading des commentaires approuvés
            $approved = $post->getComments()->filter(fn($c) => $c->isApproved());
            $post->approvedComments = $approved;
        }

        return $this->render('post/list.html.twig', [
            'posts' => $paginatedPosts,
            'queryText' => $queryText,
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
