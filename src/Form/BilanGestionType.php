<?php

namespace App\Form;

use App\Entity\Parcours;
use App\Entity\Salarie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Libs\Tools;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BilanGestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //recuperation de la liste des annees sous forme de tableau
        $outil = new Tools;
        $tabAn = $outil->get3AnneeOld(); 
        
        //liste des services
        // $services = $outil->getServices();

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
                    // 'placeholder' => 'Tous',
                    'required' => false,
                ))
                ->add('idService')
                ->add('idEntreprise')
                // ->add('salarie')
                // ->add('idservice',null,array(
                //     'label'     => 'Service',
                //     'choices' => $services,
                //     'required' => false
                // ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parcours::class,
            'data_class' => Salarie::class,
        ]);
    }
}
