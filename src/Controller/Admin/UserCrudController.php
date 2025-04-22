<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\PasswordHashService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCustomCrudController
{
    private PasswordHashService $passwordHashService;

    public function __construct(PasswordHashService $passwordHashService)
    {
        $this->passwordHashService = $passwordHashService;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('pseudo'),
            TextField::new('plainPassword')
                ->setLabel('Mot de passe')
                ->onlyOnForms()
                ->setRequired($pageName === Crud::PAGE_NEW),
            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('mapped', true),
            BooleanField::new('isBan'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->passwordHashService->hashUserPassword($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        if ($entityInstance instanceof User) {
            // Gestion du mot de passe
            $this->passwordHashService->hashUserPassword($entityInstance);
            $roles = $entityInstance->getRoles();
            // Si c'est un tableau associatif comme {"1": "ROLE_USER"}, on le convertit
            if (is_array($roles)) {
                // On extrait uniquement les valeurs et on ignore les clés
                $cleanRoles = [];
                foreach ($roles as $key => $role) {
                    if (!empty($role) && is_string($role)) {
                        $cleanRoles[] = $role;
                    }
                }
                $cleanRoles = array_unique($cleanRoles);
                if (empty($cleanRoles)) {
                    $cleanRoles = ['ROLE_USER'];
                }
                $entityInstance->setRoles($cleanRoles);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
