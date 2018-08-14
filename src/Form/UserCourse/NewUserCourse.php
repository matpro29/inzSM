<?php

namespace App\Form\UserCourse;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewUserCourse extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('course', EntityType::class, array(
                'choice_label' => 'courseFormLabel',
                'class' => Course::class,
                'label' => 'Kurs: ',
                'query_builder' => function (CourseRepository $userRepository) {
                    return $userRepository->findAllCourses();
                },
            ))
            ->add('user', EntityType::class, array(
                'choice_label' => 'userFormLabel',
                'class' => User::class,
                'label' => 'Użytkownik: ',
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findAllUsersByRole('ROLE_USER');
                },
            ))
            ->add('status', TextType::class, array(
                'label' => 'Status: ',
            ))
            ->add('add', SubmitType::class, array(
                'label' => 'Zapisz',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCourse::class,
        ]);
    }
}
