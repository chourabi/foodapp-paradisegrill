<?php

namespace App\Form;

use App\Entity\Transactions;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',NumberType::class, array(
                'required' => true,
                'label' => 'Montant',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Retrait' => 0,
                    'Ajout' => 1
                ],
                'attr'=>array('class'=>'form-control ','placeholder'=>'')
            ])
            ->add('occureDate',DateTimeType::class, array(
                'required' => true,
                'label' => 'Date',
                'attr'=>array('class'=>'form-control mb-5','placeholder'=>'')
            ))
            ->add('title',TypeTextType::class, array(
                'required' => true,
                'label' => 'Titre',
                'attr'=>array('class'=>'form-control ','placeholder'=>'')
            ))
            ->add('description',TextareaType::class, array(
                'required' => true,
                'label' => 'Description',
                'attr'=>array('class'=>'form-control','placeholder'=>'')
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transactions::class,
        ]);
    }
}
