<?php

namespace App\Form;

use App\Entity\Parcours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidationGestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('validation', ChoiceType::class, [
            'choices'  => [
                'Validé'=>"Validé",
                'Non validé'=>"Non validé",
                'Tous' => 'Tous'
                ],
            'data'      => 'Tous',//pre-selection de l'année en cours
            'label'=> "Choix parcours : ",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Parcours::class,
        ));
    }


}
