<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    //apres soumission form
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Comment) return;
        $entityInstance->setUpdatedBy($this->getUser());
        $entityInstance->setAuthor($this->getUser());

        parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

    //avant creation form
    public function createEntity(string $entityFqcn): Comment
    {
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        return $comment;
    }

    //update form
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Comment) return;
        $entityInstance->setUpdatedBy($this->getUser());
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());

        parent::updateEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content')->setLabel('Commentaire'),
            AssociationField::new('post')->setLabel('Article')->hideOnForm(),
            AssociationField::new('author')->setLabel('Auteur')->hideOnForm(),
            BooleanField::new('isApproved')->setLabel('Publier'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Commentaires')
            ->setEntityLabelInPlural('Commentaires')
            ->setEntityLabelInSingular('Commentaire')
            ->setPaginatorPageSize(10)
            ->setFormOptions([]); // autres options
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW);
    }

}
