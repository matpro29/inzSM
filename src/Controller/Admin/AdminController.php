<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        $params = [
            'user' => $user
        ];

        return $this->render('admin/admin/index.html.twig', $params);
    }
}
