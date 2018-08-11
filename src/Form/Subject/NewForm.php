<?php

namespace App\Form\Subject;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa: ',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Opis: ',
            ))
            ->add('ects', NumberType::class, array(
                'label' => 'ECTS: ',
            ))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Ćwiczenia' => 'Ćwiczenia',
                    'Laboratorium' => 'Laboratorium',
                    'Wykład' => 'Wykład',
                ),
                'label' => 'Typ: ',
            ))
            ->add('add', SubmitType::class, array(
                'label' => 'Zapisz',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class
        ]);
    }
}
