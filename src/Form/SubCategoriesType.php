<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\ProductTypes;
use App\Entity\SubCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, array(
                'required' => true,
                'label' => 'Nom',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('isActive',CheckboxType::class, array(
                'required' => false,
                'label' => 'Status( Activé / désactivé )',
                'attr'=>array('class'=>'checkbox-bloc-btn')
            ))
            ->add('productType', EntityType::class, [
                'class' => ProductTypes::class,
                'label' => 'Type de produit',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'')
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie parente',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'')
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubCategories::class,
        ]);
    }
}
