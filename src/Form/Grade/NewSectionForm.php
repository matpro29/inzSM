<?php

namespace App\Form\Grade;

use App\Entity\Grade;
use App\Entity\UserSectionGrade;
use App\Repository\GradeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewSectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grade', EntityType::class, [
                'choice_label' => 'grade',
                'class' => Grade::class,
                'label' => 'Ocena',
                'query_builder' => function (GradeRepository $gradeRepository) {
                    return $gradeRepository->findAllQB();
                }
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Komentarz'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSectionGrade::class
        ]);
    }
}
