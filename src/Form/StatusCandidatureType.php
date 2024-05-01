<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class StatusCandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('status', ChoiceType::class, [
            'label' => false, // On masque le libellé
            'choices' => [
                'Accepter' => 'acceptee',
                'Refuser' => 'refusee',
            ],
            'expanded' => true, // Permet d'afficher les choix sous forme de boutons radio
            'multiple' => false, // Pour que seul un choix soit possible
            'attr' => ['class' => 'status-buttons'], // Classe CSS facultative pour le style
        ]);
    }
}
