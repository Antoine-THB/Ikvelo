<?php

namespace App\Form;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntrepriseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('entreprise')
                //->add('created')
                //->add('updated')
                /*
                ->add('idVille', AutocompleteType::class, [
                    'class' => Ville::class,
                    //"mapped" => false,
                    ])
                ->add('idUseVille', HiddenType::class, [
                    //'class' => Ville::class,
                    "mapped" => false,
                    ])
                 * 
                 */
                ->add('adresse')
                ->add('idVille', EntityType::class, [
                    'class' => 'IsenBackOfficeBundle:Ville',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->where('u.actif=TRUE')
                                ->orderBy('u.ville', 'ASC');
                    },
                    'choice_label' => 'ville',
                ])
                ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>Entreprise::class,
        ));
    }

}
