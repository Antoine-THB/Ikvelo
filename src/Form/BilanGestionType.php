<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Mois;
use App\Entity\Parcours;
use App\Entity\Salarie;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Libs\Tools;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                    'placeholder'=>'Tous',
                    'required' => false,
                ])

                ->add('idMois', EntityType::class, [
                    'class' => Mois::class,
                    'label' => 'Mois',
                    'placeholder' => 'Tous',
                    'required' => false,
                ])
                ->add('idService', EntityType::class, [
                    'class' => Service::class,
                    'label' => 'Service',
                    'placeholder' => 'Tous',
                    'required' => false,
                ])
                ->add('idEntreprise', EntityType::class, [
                    'class' => Entreprise::class,
                    'label' => 'Entreprise',
                    'placeholder' => 'Tous',
                    'required' => false,
                ])
                ->add('idSalarie', EntityType::class, [
                    'class' => Salarie::class,
                    'label' => 'Salarie',
                    'placeholder' => 'Tous',
                    'required' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
