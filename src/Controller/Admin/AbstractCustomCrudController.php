<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

abstract class AbstractCustomCrudController extends AbstractCrudController
{
    protected ?Security $security = null;

    #[Autowire]
    public function setSecurity(Security $security): void
    {
        $this->security = $security;
    }

    public function configureActions(Actions $actions): Actions
    {
        $backToList = Action::new('backToList', 'Retour à la liste')
            ->setIcon('fa fa-arrow-left')
            ->linkToCrudAction(Action::INDEX);
        
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setLabel('Voir plus')
                    ->setIcon('fa fa-eye');
                })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
                })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
                })
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_EDIT, $backToList)
            ->add(Crud::PAGE_NEW, $backToList)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Enregistrer');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Créer');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        $entityName = (new \ReflectionClass($this->getEntityFqcn()))->getShortName();
        
        return $crud
            ->setDateFormat('dd/MM/yyyy')
            ->setTimeFormat('HH:mm')
            ->setDateTimeFormat('dd/MM/yyyy HH:mm')
            ->setPageTitle('index', $this->getPluralLabel($entityName))
            ->setPageTitle('new', 'Créer un(e) ' . $this->getSingularLabel($entityName))
            ->setPageTitle('edit', 'Modifier le ' . $this->getSingularLabel($entityName))
            ->setPageTitle('detail', 'Détails du ' . $this->getSingularLabel($entityName));
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (method_exists($entityInstance, 'setCreatedAt')) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable());
        }
        
        if (method_exists($entityInstance, 'setUpdatedAt')) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }
        
        if (method_exists($entityInstance, 'setUser') && $this->security !== null) {
            $user = $this->security->getUser();
            if ($user !== null && method_exists($entityInstance, 'getUser') && $entityInstance->getUser() === null) {
                $entityInstance->setUser($user);
            }
        }
        
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (method_exists($entityInstance, 'setUpdatedAt')) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }
        
        parent::updateEntity($entityManager, $entityInstance);
    }

    protected function createTimestampFields(): array
    {
        return [
            DateTimeField::new('createdAt')
                ->setFormat('dd/MM/yyyy')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true)
                ->setLabel('Date de création'),
                
            DateTimeField::new('updatedAt')
                ->setFormat('dd/MM/yyyy')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true)
                ->setLabel('Dernière modification'),
        ];
    }

    protected function getSingularLabel(string $entityName): string
    {
        $mapping = [
            'User' => 'Utilisateur',
            'Campaign' => 'Campagne',
            'Scenario' => 'Scénario',
            'Music' => 'Musique',
            'ImgScenario' => 'Image',
            'Rating' => 'Note',
            'Favorite' => 'Favori'
        ];
        
        return $mapping[$entityName] ?? strtolower($entityName);
    }

    protected function getPluralLabel(string $entityName): string
    {
        $mapping = [
            'User' => 'Utilisateurs',
            'Campaign' => 'Campagnes',
            'Scenario' => 'Scénarios',
            'Music' => 'Musiques',
            'ImgScenario' => 'Images',
            'Rating' => 'Notes',
            'Favorite' => 'Favoris'
        ];
        
        return $mapping[$entityName] ?? strtolower($entityName) . 's';
    }
}