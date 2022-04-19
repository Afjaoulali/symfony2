<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email'
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'titre'
            ])
            ->add('date_heure_depart')
            ->add('date_heure_fin')
            ->add('prix_total')
            ->add('date_enregistrement', DateTimeType::class, array(
                'widget' => 'single_text'
            ))
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
