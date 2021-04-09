<?php

namespace App\Form;

use App\Entity\ProductTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productType',TextType::class, array(
                'required' => true,
                'label' => 'Type de produit',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductTypes::class,
        ]);
    }
}
