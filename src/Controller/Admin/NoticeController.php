<?php

namespace App\Controller\Admin;

use App\Entity\Notice;
use App\Form\Notice\NewForm;
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
 * @Route("/admin/notice")
 */
class NoticeController extends Controller
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
     * @Route("/new", name="admin_notice_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NewForm::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $endDate = new \DateTime($notice->getEndDateString());
            $notice->setEndDate($endDate);

            $startDate = new \DateTime();
            $notice->setStartDate($startDate);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($notice);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        $params = [
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('admin/notice/new.html.twig', $params);
    }
}
