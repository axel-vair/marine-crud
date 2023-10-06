<?php

namespace App\Controller\Admin;

use App\Entity\Boat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BoatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Boat::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
