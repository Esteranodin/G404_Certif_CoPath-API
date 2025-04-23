<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CampaignCrudController extends AbstractCustomCrudController
{

    public static function getEntityFqcn(): string
    {
        return Campaign::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')
                ->setLabel('Nom de la campagne'),
            TextField::new('theme')
                ->setLabel('Thème'),
            AssociationField::new('scenarios')
                ->setLabel('Scénarios')
                ->setTemplatePath('admin/field_clickable.html.twig')
                ->setCrudController(ScenarioCrudController::class) 
                ->onlyOnDetail(),
                
            // Lors création Campagne, est-ce qu'on peut ajouter des scénarios ?
            // AssociationField::new('scenarios')
            //     ->setLabel('Scénarios')
            //     ->setCrudController(ScenarioCrudController::class)
            //     ->onlyOnForms(),
            // ...$this->createTimestampFields(),
        ];
    }

}
