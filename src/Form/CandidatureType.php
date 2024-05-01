<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('email')
            ->add('Niveau')
            ->add('Ville')
            ->add('imageFileCV', VichFileType::class, [
                'label' => 'CV', // Libellé pour le champ imageFileCV
            ])
            ->add('imageFileLettreMotivation', VichFileType::class, [
                'label' => 'Lettre de motivation', // Libellé pour le champ imageFileLettreMotivation
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
