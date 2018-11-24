<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Notice;
use App\Form\Notice\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/notice")
 */
class NoticeController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }


    /**
     * @Route("/new/{id}", name="course_notice_new", methods="GET|POST")
     */
    public function new(Course $course, Request $request): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NewForm::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notice->setCourse($course);

            $endDate = new \DateTime($notice->getEndDateString());
            $notice->setEndDate($endDate);

            $startDate = new \DateTime();
            $notice->setStartDate($startDate);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($notice);
            $entityManager->flush();

            $params = [
                'id' => $course->getId()
            ];

            return $this->redirectToRoute('course_show', $params);
        }

        $user = $this->getUser();

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'user' => $user
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/notice/new.html.twig', $params);
    }
}
