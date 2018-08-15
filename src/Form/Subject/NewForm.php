<?php

namespace App\Form\Subject;

use App\Entity\Subject;
use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('type', EntityType::class, array(
                'choice_label' => 'name',
                'class' => Type::class,
                'label' => 'Typ: ',
                'query_builder' => function (TypeRepository $typeRepository) {
                    return $typeRepository->findAllQB();
                },
            ))
            ->add('add', SubmitType::class, array(
                'label' => 'Zapisz',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class
        ]);
    }
}
