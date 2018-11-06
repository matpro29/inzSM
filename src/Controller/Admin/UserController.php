<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/user", name="admin_user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $users = $userRepository->findAll();

        $params = [
            'user' => $user,
            'users' => $users
        ];

        return $this->render('admin/user/index.html.twig', $params);
    }

    /**
     * @Route("/user/{id}", name="admin_user_info", methods="GET")
     */
    public function info(User $userInfo): Response
    {
        $user = $this->getUser();

        $params = [
            'user' => $user,
            'userInfo' => $userInfo
        ];

        return $this->render('admin/user/info.html.twig', $params);
    }

    /**
     * @Route("/user/{id}", name="admin_user_promote", methods="PROMOTE")
     */
    public function promote(Request $request, User $userInfo): Response
    {
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('promote'.$userInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $userInfo->setRoles('ROLE_TEACHER');

            $entityManager->flush();
        }

        $params = [
            'user' => $user,
            'userInfo' => $userInfo
        ];

        return $this->render('admin/user/info.html.twig', $params);
    }
}
