<?php

namespace App\Controller\Admin;

use App\Entity\ImgScenario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImgScenarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImgScenario::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('imgPath'),    
            TextField::new('imgAlt'),    
            ArrayField::new('scenario')->onlyOnIndex(),
            AssociationField::new('scenario')->onlyOnForms(),
        ];
    }
}
