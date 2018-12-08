<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\User\SearchForm;
use App\Repository\ConversationRepository;
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

    public function __construct(ConversationRepository $conversationRepository,
                                NoticeRepository $noticeRepository,
                                Security $security,
                                UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/", name="admin_user_index", methods="GET|POST")
     */
    public function index(Request $request,
                          UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(SearchForm::class, $user);
        $form->handleRequest($request);

        $users = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findAllBySearchForm($user->getUsername());
        } else {
            $users = $userRepository->findAll();
        }

        $params = [
            'users' => $users,
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('admin/user/index.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="admin_user_info", methods="GET")
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
     * @Route("/{id}", name="admin_user_promote", methods="PROMOTE")
     */
    public function promote(Request $request,
                            User $userInfo): Response
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
