<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'CDD' => 'CDD',
                    'CDI' => 'CDI',
                    'Temps plein' => 'Temps plein',
                    'Temps partiel' => 'Temps partiel',
                    'CIVP' => 'CIVP',
                ],
                'attr' => [
                        'label' => 'Type de contrat',
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
