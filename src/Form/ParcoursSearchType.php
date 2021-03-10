<?php

namespace App\Form;

use App\Entity\Parcours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isen\BackOfficeBundle\Libs\Tools;

class ParcoursSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //recuperation de la liste des annees sous forme de tableau
        $outil = new Tools;
        $tabAn = $outil->get3AnneeOld(); 
        
        //mois en cours
        $madate = new \DateTime();
        $cemois = $madate->format('n');
        
        $builder
                ->add('annee', ChoiceType::class, [
                    'choices'  => [
                        $tabAn[0] => $tabAn[0],
                        $tabAn[1] => $tabAn[1],
                        $tabAn[2] => $tabAn[2],
                        ],
                    'data'      => $tabAn[0],//pre-selection de l'année en cours
                ])
                
                ->add('idMois',null,array(
                    'label'     => 'Mois',
                    'placeholder' => 'Tous',
                    'required' => false,
                    //'data'      => $cemois
                    ))
                
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Parcours::class,
        ));
    }



}
