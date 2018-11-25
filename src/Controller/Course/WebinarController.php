<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Webinar;
use App\Form\Webinar\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Repository\WebinarRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/webinar")
 */
class WebinarController extends Controller
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
     * @Route("/end/{id}", name="course_webinar_end", methods="GET|POST")
     */
    public function end(Course $course,
                        WebinarRepository $webinarRepository): Response
    {
        $webinar = $webinarRepository->findOneActiveByCourseId($course->getId());
        $webinar->setIsActive(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($webinar);
        $entityManager->flush();

        $params = [
            'id' => $course->getId()
        ];

        return $this->redirectToRoute('course_webinar_index', $params);
    }

    /**
     * @Route("/{id}", name="course_webinar_index", methods="GET|POST")
     */
    public function index(Course $course,
                          WebinarRepository $webinarRepository): Response
    {
        $webinar = $webinarRepository->findOneActiveByCourseId($course->getId());
        $webinars = $webinarRepository->findAllNotActiveByCourseId($course->getId());

        $websiteDomain = $this->getParameter('website_domain');

        $params = [
            'course' => $course,
            'webinar' => $webinar,
            'webinars' => $webinars,
            'websiteDomain' => $websiteDomain
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/webinar/index.html.twig', $params);
    }

    /**
     * @Route("/new/{id}", name="course_webinar_new", methods="GET|POST")
     */
    public function new(Course $course,
                        Request $request): Response
    {
        $webinar = new Webinar();

        $form = $this->createForm(NewForm::class, $webinar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $webinar->setCourse($course);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($webinar);
            $entityManager->flush();

            $params = [
                'id' => $course->getId()
            ];

            return $this->redirectToRoute('course_webinar_index', $params);
        }


        $params = [
            'course' => $course,
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/webinar/new.html.twig', $params);
    }
}
