<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\Interfaces\HasCreatedAtInterface;
use App\Entity\Interfaces\HasUpdatedAtInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class CampaignCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Campaign::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('theme'),
            DateTimeField::new('createdAt')
                ->setFormat('dd/MM/yyyy')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
            DateTimeField::new('updatedAt')
                ->setFormat('dd/MM/yyyy')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof HasCreatedAtInterface) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable());
        }
        if ($entityInstance instanceof HasUpdatedAtInterface) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }

            if ($entityInstance instanceof Campaign && $entityInstance->getUser() === null) {
                $user = $this->security->getUser();

        parent::persistEntity($entityManager, $entityInstance);
            }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof HasUpdatedAtInterface) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
