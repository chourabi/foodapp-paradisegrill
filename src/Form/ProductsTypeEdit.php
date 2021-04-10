<?php

namespace App\Form;

use App\Entity\Products;
use App\Entity\SubCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductsTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, array(
                'required' => true,
                'label' => 'Nom',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('prepTime',IntegerType::class, array(
                'required' => false,
                'label' => 'Temp de préparation ( en minute )',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('price',NumberType::class, array(
                'required' => true,
                'label' => 'Prix',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('quantity',IntegerType::class, array(
                'required' => true,
                'label' => 'Quantité',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('isActive',CheckboxType::class, array(
                'required' => false,
                'label' => 'Status( Activé / désactivé )',
                'attr'=>array('class'=>'checkbox-bloc-btn')
            ))
            ->add('isPromoted',CheckboxType::class, array(
                'required' => false,
                'label' => 'Voir en premier',
                'attr'=>array('class'=>'checkbox-bloc-btn')
            ))
            ->add('photoURL', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=>array('class'=>'form-control','placeholder'=>''),

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                    ])
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => SubCategories::class,
                'label' => 'Catégorie',
                'attr'=>array('class'=>'form-control select-custom-drop','placeholder'=>'')
                
            ])
            ->add('description',TextareaType::class, array(
                'required' => true,
                'label' => 'Description',
                'attr'=>array('class'=>'form-control','placeholder'=>'Description','rows'=>'4')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
