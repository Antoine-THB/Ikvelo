<?php

namespace App\Form;

use App\Entity\Salarie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;;

class SalarieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('casUsername')
                ->add('nom')
                ->add('prenom')
                ->add('adresse')
                ->add('distance', TextType::class)
                ->add('urlGeovelo')
                //->add('created')
                //->add('updated')
                
                //->add('idVille')
                
                ->add('idVille', EntityType::class, [
                    'class' => Ville::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->where('u.actif=TRUE')
                                ->orderBy('u.ville', 'ASC');
                    },
                    'choice_label' => 'ville',
                ])
                 
                //->add('idVille')            
                ->add('idService')
                ->add('idEntreprise')
                ->add('idRole')
                ->add('actif')
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Salarie::class,
        ));
    }



}
