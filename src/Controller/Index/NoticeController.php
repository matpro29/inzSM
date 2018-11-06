<?php

namespace App\Controller\Index;

use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/notice")
 */
class NoticeController extends Controller
{
    /**
     * @Route("/", name="notice_index", methods="GET")
     */
    public function index(NoticeRepository $noticeRepository): Response
    {
        $user = $this->getUser();
        $notices = $noticeRepository->findAllByUserId($user->getId());

        $params = [
            'notices' => $notices,
            'user' => $user
        ];

        return $this->render('index/notice/index.html.twig', $params);
    }
}
