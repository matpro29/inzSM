<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/user", name="admin_user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        $params = [
            'users' => $users
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('admin/user/index.html.twig', $params);
    }

    /**
     * @Route("/user/{id}", name="admin_user_info", methods="GET")
     */
    public function info(User $userInfo): Response
    {
        $params = [
            'userInfo' => $userInfo
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('admin/user/info.html.twig', $params);
    }

    /**
     * @Route("/user/{id}", name="admin_user_promote", methods="PROMOTE")
     */
    public function promote(Request $request, User $userInfo): Response
    {
        if ($this->isCsrfTokenValid('promote'.$userInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $userInfo->setRoles('ROLE_TEACHER');

            $entityManager->flush();
        }

        $params = [
            'userInfo' => $userInfo
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('admin/user/info.html.twig', $params);
    }
}
