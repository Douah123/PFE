<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Entity\Cateory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Offres')
        ->setEntityLabelInSingular('Offre')
        ->setPageTitle('index', 'Administration des Offres');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('type'),
            TextField::new('location'),
            TextField::new('salary'),
            AssociationField::new('category'),
            TextEditorField::new('description')->hideOnIndex(),
            //DateTimeField::new('createdAt')->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            //DateTimeField::new('ExpiresAt')->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            
        ];
    }
    
}
