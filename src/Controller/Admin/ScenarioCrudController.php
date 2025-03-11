<?php

namespace App\Controller\Admin;

use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScenarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Scenario::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex(),
            TextField::new('title'),
            TextareaField::new('content'),
            ArrayField::new('campaign')->onlyOnIndex(),
            AssociationField::new('campaign')->onlyOnForms(),
        ];
    }
    
}
