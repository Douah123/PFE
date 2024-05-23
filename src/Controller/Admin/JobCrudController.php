<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;



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
        ->setPageTitle('index', 'Administration des Offres')
        ->setPaginatorPageSize(5);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('title')->setlabel('Titre'),
            TextField::new('type')->setlabel('Type de contrat'),
            TextField::new('location')->setlabel('Emplacement'),
            TextField::new('salary')->setlabel('Salaire'),
            AssociationField::new('category')->setlabel('Categorie'),
            TextEditorField::new('description')->hideOnIndex()->setlabel('Description'),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('imageName')->setBasePath('/assets/img/')->setLabel('Image')->setUploadDir('/public/images/job')->hideOnIndex(),
                            
            //ImageField::new('imageName')->setLabel('Image')->onlyOnIndex(),
            //DateTimeField::new('createdAt')->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            //DateTimeField::new('ExpiresAt')->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            
        ];
    }
    
}
