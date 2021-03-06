<?php

namespace App\Form;

use App\Entity\OptionItems;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom',TextType::class, array(
                'required' => true,
                'label' => 'Nom',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('isActive',CheckboxType::class, array(
                'required' => false,
                'label' => 'Status( Activé / désactivé )',
                'attr'=>array('class'=>'checkbox-bloc-btn')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionItems::class,
        ]);
    }
}
