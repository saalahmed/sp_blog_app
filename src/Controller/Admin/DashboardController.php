<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private UserRepository $userRepo;
    private PostRepository $postRepo;
    private CommentRepository $commentRepo;

    public function __construct(
        UserRepository $userRepo,
        PostRepository $postRepo,
        CommentRepository $commentRepo
    ) {
        $this->userRepo = $userRepo;
        $this->postRepo = $postRepo;
        $this->commentRepo = $commentRepo;
    }


    public function index(): Response
    {

        $userCount = $this->userRepo->count([]);
        $postCount = $this->postRepo->count([]);
        $commentCount = $this->commentRepo->count([]);
        $postPublished = $this->postRepo->count(['isPublished' => true]);
        $postUnpublished = $this->postRepo->count(['isPublished' => false]);
        $commentPublished = $this->commentRepo->count(['isApproved' => true]);
        $commentUnpublished = $this->commentRepo->count(['isApproved' => false]);
        $userActive = $this->userRepo->count(['isActive' => true]);
        $userInactive = $this->userRepo->count(['isActive' => false]);
        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userCount,
            'postCount' => $postCount,
            'commentCount' => $commentCount,
            'postPublished' => $postPublished,
            'postUnpublished' => $postUnpublished,
            'commentPublished' => $commentPublished,
            'commentUnpublished' => $commentUnpublished,
            'userActive'=> $userActive,
            'userInactive' => $userInactive,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fas fa-tags', Post::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
