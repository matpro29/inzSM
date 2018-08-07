<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function admin(UserRepository $userRepository)
    {
        return $this->render('admin/admin.html.twig', [
            'user' => $userRepository->findAll()]);
    }
}
