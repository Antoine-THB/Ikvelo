<?php

namespace App\Form;

use App\Entity\DeclarationHonneur;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DeclarationHonneurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idSalarie')
                ->add('idEntreprise')
                
                ->add('adresse')
                ->add('idVille', EntityType::class, [
                    'class' => Ville::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->where('u.actif=TRUE')
                                ->orderBy('u.ville', 'ASC');
                    },
                    'choice_label' => 'ville',
                ])
                ->add('distance')
                ->add('prime')
                ->add('urlGeovelo')
                
                
                ->add('actif')
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DeclarationHonneur::class,
        ));
    }




}
