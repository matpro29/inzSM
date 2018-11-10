<?php

namespace App\Form\Grade;

use App\Entity\Grade;
use App\Entity\Section;
use App\Entity\UserSectionGrade;
use App\Repository\GradeRepository;
use App\Repository\SectionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewSectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {dump($options);
        $builder
            ->add('section', EntityType::class, [
                'choice_label' => 'name',
                'class' => Section::class,
                'label' => 'Sekcja: ',
                'query_builder' => function (SectionRepository $sectionRepository) {
                    return $sectionRepository->findAllByUserIdQB(4);
                }
            ])
            ->add('grade', EntityType::class, [
                'choice_label' => 'grade',
                'class' => Grade::class,
                'label' => 'Ocena: ',
                'query_builder' => function (GradeRepository $gradeRepository) {
                    return $gradeRepository->findAllQB();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSectionGrade::class,
            'userId' => 0
        ]);
    }
}
