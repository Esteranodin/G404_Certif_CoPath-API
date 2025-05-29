<?php

namespace App\Controller\Admin;

use App\Entity\ImgScenario;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImgScenarioCrudController extends AbstractCustomCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImgScenario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('imgPath')
            ->setLabel('Chemin de l\'image')
            ->setBasePath('uploads/images')
            ->setUploadDir('public/uploads/scenarios-covers')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('imgAlt')
                ->setLabel('Texte alternatif'),
            AssociationField::new('scenario')
                ->setCrudController(ScenarioCrudController::class),
        ];
    }
}
