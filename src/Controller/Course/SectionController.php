<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Section;
use App\Form\Course\SectionNewFom;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course/section")
 */
class SectionController extends Controller
{
    /**
     * @Route("/{id}", name="course_section_delete", methods="DELETE")
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_show', [
            'id' => $section->getCourse()->getId()
        ]);
    }

    /**
     * @Route("/new/{id}", name="course_section_new", methods="GET|POST")
     */
    public function new(Course $course, Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionNewFom::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setCourse($course);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('course_show', [
                'id' => $course->getId()
            ]);
        }

        return $this->render('course/section/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
            'section' => $section
        ]);
    }
}
