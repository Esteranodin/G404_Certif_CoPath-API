<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
                ->setLabel('Nom de la campagne')
                ->setRequired(true),
            TextField::new('theme')
                ->setLabel('Thème'),
            ...$this->createTimestampFields(),
        ];
    }

}
