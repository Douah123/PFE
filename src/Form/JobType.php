<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('type', TextType::class, [
                
                'attr' => [
                        'placeholder' => 'CDI,CDD,temps plein...autre',
                 ],
            ])
            ->add('location')
            ->add('salary')
            ->add('category')
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'ckeditor'],
            ])
            ->add('imageFile', VichImageType::class,)
            #->add('createdAt')
            #->add('updatedAt')
            #->add('expiresAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
