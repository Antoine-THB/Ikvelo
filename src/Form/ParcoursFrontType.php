<?php

namespace App\Form;

use App\Entity\Parcours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isen\BackOfficeBundle\Libs\Tools;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParcoursFrontType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //recuperation de la liste des annees sous forme de tableau
        $outil = new Tools;
        $tabAn = $outil->get3Annee(); 
        
        //mois en cours
        $madate = new \DateTime();
        $cemois = $madate->format('n');
        
        $builder//->add('annee')
                /*
                ->add('annee', ChoiceType::class, [
                    'choices'  => [
                        '2018' => '2018',
                        '2019' => '2019',
                        '2020' => '2020',
                    ],
                ])
                */
                ->add('annee', ChoiceType::class, [
                    'choices'  => [
                        $tabAn[0] => $tabAn[0],
                        $tabAn[1] => $tabAn[1],
                        $tabAn[2] => $tabAn[2],
                        ],
                    'data'      => $tabAn[1],//pre-selection de l'année en cours
                ])
                
                ->add('idMois',null,array(
                    'label'     => 'Mois',
                    
                    //'data'      => $cemois
                    ))
                
               /*
                ->add('idMois',EntityType::class, [
                    'class' => 'IsenBackOfficeBundle:Mois',
                    'choice_label' => 'mois',
                    'data'      => 'mai',
                    'preferred_choices' => function ($mois) {
                            return $mois->getMoisCourant();
                        }
                ])
                        */
                ->add('veloUniq',ChoiceType::class,array(
                    'choices'   => [
                            'Oui'   => true,
                            'Non'   => false,
                            ],
                    'expanded'  => true,
                    'multiple'  => false,
                    'data'      => true,//pre-selection 
                    'label'     => 'Vélo uniquement'
                    ))
                ->add('descriptTrajet',null,array('label' => 'Trajet'))
                ->add('distanceBase',null,array('label' => 'Distance'))
                //->add('nbKmEffectue')
                //->add('indemnisation',null,array('label' => 'Ecole'))
                //->add('dateCreation')
                //->add('cloture')
                //->add('validation')
                //->add('created')
                //->add('updated')
                
                //->add('idSalarie')
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Parcours::class,
        ));
    }

}
