<?php

namespace App\Controller\Admin;

use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScenarioCrudController extends AbstractCustomCrudController
{
    public static function getEntityFqcn(): string
    {
        return Scenario::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextareaField::new('content', 'Contenu')
                ->setTemplatePath('admin/scenario_content_preview.html.twig'),
            AssociationField::new('campaign', 'Campagnes')
                ->setTemplatePath('admin/field_clickable.html.twig')
                ->setCrudController(CampaignCrudController::class)
                ->hideOnForm(),
            AssociationField::new('campaign', 'Campagnes')
                ->onlyOnForms(),
            ...$this->createTimestampFields(),
            AssociationField::new('user', 'Créer par')
                ->setCrudController(UserCrudController::class)
                ->hideOnIndex(),
            ImageField::new('imgScenario.imgPath')
                ->setBasePath('/uploads/scenarios-covers/')
                ->setLabel('Aperçu image')
                ->onlyOnDetail(),
        ];
    }
}
