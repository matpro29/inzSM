<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Route("/users", name="admin_users", methods="GET")
     */
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/users.html.twig', [
            'user' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/users/{id}", name="admin_users_promote", methods="PROMOTE")
     */
    public function userPromote(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('promote'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setRoles('ROLE_TEACHER');

            $entityManager->flush();
        }

        return $this->render('admin/users/users_show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/users/{id}", name="admin_users_show", methods="GET")
     */
    public function usersShow(User $user): Response
    {
        return $this->render('admin/users/users_show.html.twig', [
                'user' => $user
        ]);
    }
}
