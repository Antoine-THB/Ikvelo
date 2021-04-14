<?php

namespace App\Form;

use App\Entity\Salarie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SalarieFrontType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('casUsername')
                ->add('nom',null,array('label' => 'Nom'))
                ->add('prenom',null,array('label' => 'Prénom'))
                ->add('adresse',null,array('label' => 'Adresse'))
                
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
                    'choice_label' => 'Ville',
                ])
                ->add('distance',null,array('label' => 'Distance initiale'))
                ->add('urlGeovelo',null,array('label' => 'URL de validation du parcours'))
                 
                //->add('idVille')            
                ->add('idService',null,array('label' => 'Service'))
                ->add('idEntreprise',null,array('label' => 'Ecole'))
                ->add('tpsTravail',null,array('label' => 'Temps travaillé (%)'))
                //->add('idRole')
                //->add('actif')
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Salarie::class,
        ));
    }


}
