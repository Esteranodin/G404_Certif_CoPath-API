<?php

namespace App\Controller\Admin;

use App\Entity\Music;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class MusicCrudController extends AbstractCustomCrudController
{
    public static function getEntityFqcn(): string
    {
        return Music::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            UrlField::new('musicPath')
                ->setLabel('Chemin de la musique'),
            AssociationField::new('scenario')
                ->setCrudController(ScenarioCrudController::class),
        ];
    }
}
