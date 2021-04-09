<?php

namespace App\Form;

use App\Entity\Options;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array(
                'required' => true,
                'label' => 'Nom',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('nombreMaximumOption',IntegerType::class, array(
                'required' => true,
                'label' => "Nombre maximum d'options",
                'attr'=>array('class'=>'form-control','placeholder'=>"")
            ))
            ->add('prixOptionSupp',NumberType::class, array(
                'required' => true,
                'label' => 'Prix Option Supplémentaire',
                'attr'=>array('class'=>'form-control','placeholder'=>"")
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Options::class,
        ]);
    }
}
