<?php

namespace App\Form\Grade;

use App\Entity\Grade;
use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grade', DecimalType::class, [
                'label' => 'Ocena'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Grade::class,
        ]);
    }
}
