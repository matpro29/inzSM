<?php

namespace App\Controller\Index;

use App\Repository\NoticeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/notice")
 */
class NoticeController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/", name="notice_index", methods="GET")
     */
    public function index(NoticeRepository $noticeRepository): Response
    {
        $params = $this->parameter->getParams($this, []);

        $notices = $noticeRepository->findAllByUserId($params['user']->getId());
        $params['notices'] = $notices;

        $dateTime = new \DateTime();
        $params['user']->setNoticeDate($dateTime);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('index/notice/index.html.twig', $params);
    }
}
