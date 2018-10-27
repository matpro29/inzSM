<?php

namespace App\Controller\Index;

use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function index(NoticeRepository $noticeRepository, UserInterface $user): Response
    {
        $notices = $noticeRepository->findAllByUserIdWithLimit(5,$user->getId());

        return $this->render('index/index/index.html.twig', [
            'notices' => $notices
        ]);
    }

    /**
     * @Route("/", name="public")
     */
    public function public(): Response
    {
        return $this->render('index/index/public.html.twig');
    }
}