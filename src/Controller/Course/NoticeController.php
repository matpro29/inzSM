<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Notice;
use App\Form\Notice\NewForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course/notice")
 */
class NoticeController extends Controller
{
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($notice);
            $entityManager->flush();

            return $this->redirectToRoute('course_show', [
                'id' => $course->getId()
            ]);
        }

        return $this->render('course/notice/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
            'notice' => $notice
        ]);
    }
}
