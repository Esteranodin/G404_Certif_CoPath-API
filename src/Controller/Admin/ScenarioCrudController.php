<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\Interfaces\HasCreatedAtInterface;
use App\Entity\Interfaces\HasUpdatedAtInterface;
use App\Entity\Scenario;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Scenario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextareaField::new('content'),
            ArrayField::new('campaign')->onlyOnIndex(),
            AssociationField::new('campaign')->onlyOnForms(),
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
