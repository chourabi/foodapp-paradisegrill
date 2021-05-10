<?php

namespace App\Form;

use App\Entity\Options;
use App\Entity\OptionToProducts;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionToProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'label' => 'Produit',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'','disabled'=>'disabled')
                
            ])
            ->add('productOption', EntityType::class, [
                'class' => Options::class,
                'label' => 'Option',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'')
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionToProducts::class,
        ]);
    }
}
