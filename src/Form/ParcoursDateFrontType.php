<?php

namespace App\Form;

use App\Entity\ParcoursDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Isen\BackOfficeBundle\Libs\Tools;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ParcoursDateFrontType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $myvalue = $options['mois'];
        //$monmois = $data->getMois();
        
         //recuperation de la liste des jours sous forme de tableau
         $outil = new Tools;
        // $listeJours = $outil->getJour4MoisAnnee('c', $options['mois'], $options['annee']);
         $listeJours = $outil->getJour4MoisAnnee('v', $options['mois'], $options['annee']);
         // $listeJours = $outil->getJour4MoisAnnee('v', 4, $options['annee']);
         
        $builder
                //->add('jourLabel')
                //->add('jourLabel',null,['label' => $myvalue ])
                ->add('jourNum',ChoiceType::class,[
                                            'choices'   => $listeJours,
                                            'label'     => 'Jour',
                        ])
                //->add('utilisationVelo')
                ->add('commentaire')
                ->add('idTypeTrajet',null,['placeholder' => false,'label'     => 'Type de trajet'])
                ->add('nbKmEffectue')
                ->add('indemnisation',null,['disabled' => true])
                ->add('kminit', HiddenType::class, [
                    'data' => $options['nbkminit'],
                    'mapped'   => false,
                ])
                ->add('coef', HiddenType::class, [
                    'data' => $options['coef'],
                    'mapped'   => false,
                ])
                //->add('created')
                //->add('updated')
                
                //->add('idParcours')
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParcoursDate::class,
            'mois'      => null,
            'annee'     => null,
            'nbkminit'  => null,
            'coef'     => null
        ));
    }



}
