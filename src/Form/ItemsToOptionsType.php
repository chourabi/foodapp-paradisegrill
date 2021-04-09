<?php

namespace App\Form;

use App\Entity\ItemsToOptions;
use App\Entity\OptionItems;
use App\Entity\Options;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemsToOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('linkedOption', EntityType::class, [
                'class' => Options::class,
                'label' => 'Option',
                'attr'=>array('class'=>'form-control select-custom-drop','disabled'=>'disabled')
            ])
            ->add('linkedItem', EntityType::class, [
                
                'class' => OptionItems::class,
                'label' => 'Item',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemsToOptions::class,
        ]);
    }
}
