<?php

namespace App\Controller\Index;

use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $params = $this->parameter->getParams($this, []);

        return $this->render('index/index/index.html.twig', $params);
    }

    /**
     * @Route("/", name="public")
     */
    public function public(): Response
    {
        $params = $this->parameter->getParams($this, []);

        return $this->render('index/index/public.html.twig', $params);
    }
}
