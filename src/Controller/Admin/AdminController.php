<?php

namespace App\Controller\Admin;

use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
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
     * @Route("/", name="admin_index")
     */
    public function index(): Response
    {
        $params = $this->parameter->getParams($this, []);

        return $this->render('admin/admin/index.html.twig', $params);
    }
}
