<?php

namespace App\Controller\Admin;

use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            TextField::new('title')
                ->setLabel('Titre'),
            TextField::new('content', 'Contenu')
                ->setTemplatePath('admin/scenario_content_preview.html.twig')
                ->hideOnForm(),
            AssociationField::new('campaign')
                ->setLabel('Campagnes')
                ->setTemplatePath('admin/field_clickable.html.twig')
                ->setCrudController(CampaignCrudController::class)
                ->hideOnForm(),
            AssociationField::new('campaign')
                ->setLabel('Campagnes')
                ->onlyOnForms(),
            ...$this->createTimestampFields(),
            AssociationField::new('user')
                ->setLabel('Créer par')
                ->setCrudController(UserCrudController::class)
                ->hideOnIndex(),
            ImageField::new('imgScenario.imgPath')
                ->setBasePath('/uploads/scenarios-covers/')
                ->setLabel('Aperçu image')
                ->onlyOnDetail(),
        ];
    }
}
