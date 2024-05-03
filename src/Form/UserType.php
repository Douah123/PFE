<?php

namespace App\Form;

use App\Entity\Employeur;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => [
                'placeholder' => 'Entrez votre nouvel adresse email'
            ]
        ])
            ->add('firstName')
            ->add('lastName')
            ->add('plainPassword', PasswordType::class, [
                
                'mapped' => false,
                'attr' => [
                    
                    'autocomplete' => 'new-password',
                ],
                
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*\d).+$/',
                        'message' => 'Your password must contain at least one uppercase letter and one digit.',
                    ]),
                ],
            ])
        
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-block' 
                ]
            ])
           
            
        ;
            
            
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
