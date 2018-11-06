<?php

namespace App\Controller\Index;

use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function index(NoticeRepository $noticeRepository): Response
    {
        $user = $this->getUser();
        $notices = $noticeRepository->findAllByUserIdWithLimit(5,$user->getId());

        $params = [
            'notices' => $notices,
            'user' => $user
        ];

        return $this->render('index/index/index.html.twig', $params);
    }

    /**
     * @Route("/", name="public")
     */
    public function public(): Response
    {
        $user = $this->getUser();

        $params = [
            'user' => $user
        ];

        return $this->render('index/index/public.html.twig', $params);
    }
}
