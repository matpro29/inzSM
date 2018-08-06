<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $userRepository)
    {
        return $this->render('admin/admin.html.twig', [
            'user' => $userRepository->findAll()]);
    }
}
