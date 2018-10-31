<?php

namespace App\Form\Task;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa: '
            ])
            ->add('is_file', CheckboxType::class, [
                'label' => 'Plik: ',
                'required' => false
            ])
            ->add('is_date', CheckboxType::class, [
                'label' => 'Ogranicz czas: ',
                'required' => false
            ])
            ->add('start_date', DateTimeType::class, [
                'label' => 'Data początkowa: ',
                ''
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Data końcowa: '
            ])
            ->add('contents', TextareaType::class, [
                'label' => 'Treść: '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class
        ]);
    }
}
