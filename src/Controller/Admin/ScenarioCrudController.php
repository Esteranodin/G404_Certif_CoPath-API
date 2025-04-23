<?php

namespace App\Controller\Admin;

use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            IdField::new('id')->hideOnForm(),
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
            // Version éditable pour les formulaires
            AssociationField::new('campaign')
                ->setLabel('Campagnes')
                ->onlyOnForms(),
            ...$this->createTimestampFields(),
        ];
    }
}
