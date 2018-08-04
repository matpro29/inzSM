<?php

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('label' => 'Nazwa użytkownika: '))
            ->add('password', PasswordType::class, array('label' => 'Hasło: '))
            ->add('login', SubmitType::class, array('label' => 'Zaloguj'))
            ->getForm();

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
