<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Section;
use App\Form\Section\NewForm;
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
 * @Route("/course/section")
 */
class SectionController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }


    /**
     * @Route("/{id}", name="course_section_delete", methods="DELETE")
     */
    public function delete(Request $request, Section $section): Response
    {
        $course = $section->getCourse();

        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        $params = [
            'id' => $course->getId()
        ];

        return $this->redirectToRoute('course_show', $params);
    }

    /**
     * @Route("/new/{id}", name="course_section_new", methods="GET|POST")
     */
    public function new(Course $course, Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(NewForm::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setCourse($course);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            $params = [
                'id' => $course->getId()
            ];

            return $this->redirectToRoute('course_show', $params);
        }

        $params = [
            'course' => $course,
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/section/new.html.twig', $params);
    }
}
