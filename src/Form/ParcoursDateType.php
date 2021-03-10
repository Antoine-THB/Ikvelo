<?php

namespace App\Form;

use App\Entity\ParcoursDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParcoursDateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('jourLabel')->add('jourNum')->add('utilisationVelo')->add('commentaire')->add('nbKmEffectue')->add('indemnisation')->add('created')->add('updated')->add('idTypeTrajet')->add('idParcours');
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParcoursDate::class,
        ));
    }

}
