<?php

namespace App\Controller\Admin;

use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            IdField::new('id')->hideOnForm(),
            TextField::new('title')
                ->setLabel('Titre'),
            TextareaField::new('content')
                ->setLabel('Description')
                ->setFormTypeOption('attr', ['rows' => 10])
                ->formatValue(function ($value) {
                    if (strlen($value) > 50) {
                        return substr($value, 0, 50) . '...';
                    }
                    return $value;
                }),
            AssociationField::new('campaign')
                ->setCrudController(CampaignCrudController::class)
                ->setLabel('Campagne')
                ->setFormTypeOption('choice_label', 'name'),
                // ->formatValue(function ($value) {
                //     return $value ? $value->getName() : '';
                // }),
            ...$this->createTimestampFields(),
        ];
    }
}
