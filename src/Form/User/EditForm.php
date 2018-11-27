<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nazwa użytkownika'
            ])
            ->add('index', TextType::class, [
                'label' => 'Index',
                'required' => false
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Imię'
            ])
            ->add('secondName', TextType::class, [
                'label' => 'Drugie imię',
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('city', TextType::class, [
                'label' => 'Miejscowość'
            ])
            ->add('street', TextType::class, [
                'label' => 'Ulica',
                'required' => false
            ])
            ->add('house', TextType::class, [
                'label' => 'Nr domu'
            ])
            ->add('flat', TextType::class, [
                'label' => 'Nr mieszkania',
                'required' => false
            ])
            ->add('PESEL', TextType::class, [
                'label' => 'PESEL'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
