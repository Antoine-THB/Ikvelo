<?php

namespace App\Form;

use App\Entity\Parcours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ParcoursType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idSalarie')
                ->add('annee')
                ->add('idMois')
                ->add('veloUniq')
                ->add('descriptTrajet')
                ->add('distanceBase')
                ->add('nbKmEffectue', TextType::class)
                ->add('indemnisation', TextType::class)
                //->add('dateCreation',null,['disabled' => true])
                ->add('cloture')
                ->add('validation')
                //->add('created',null,['disabled' => true])
                //->add('updated',null,['disabled' => true])
                
                
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Parcours::class,
        ));
    }
}
