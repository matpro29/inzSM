<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LogoutController extends Controller
{
    public function logout()
    {
        return $this->render('user/logout/logout.html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}
