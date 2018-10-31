<?php

namespace App\Controller\Course;

use App\Entity\Section;
use App\Entity\Task;
use App\Form\Task\NewForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course/section/task")
 */
class TaskController extends Controller
{
    /**
     * @Route("/new/{id}", name="course_task_new", methods="GET|POST")
     */
    public function new(Request $request, Section $section): Response
    {
        $task = new Task();
        $form = $this->createForm(NewForm::class, $task);
        $form->handleRequest($request);

        $course = $section->getCourse();

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setSection($section);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
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

        return $this->render('course/task/new.html.twig', $params);
    }
}