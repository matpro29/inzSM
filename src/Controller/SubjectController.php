<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\Subject\NewForm;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subject")
 */
class SubjectController extends Controller
{
    /**
     * @Route("/{id}", name="subject_delete", methods="DELETE")
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subject_index');
    }

    /**
     * @Route("/edit/{id}", name="subject_edit", methods="GET|POST")
     */
    public function edit(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(NewForm::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $subject->getId()
            ];

            return $this->redirectToRoute('subject_edit', $params);
        }

        $params = [
            'form' => $form->createView(),
            'subject' => $subject
        ];

        return $this->render('subject/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="subject_index", methods="GET")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findAll();

        $params = [
            'subjects' => $subjects
        ];

        return $this->render('subject/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="subject_info", methods="GET")
     */
    public function info(Subject $subject): Response
    {
        $params = [
            'subject' => $subject
        ];

        return $this->render('subject/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="subject_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(NewForm::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('subject_index');
        }

        $params = [
            'form' => $form->createView(),
            'subject' => $subject
        ];

        return $this->render('subject/new.html.twig', $params);
    }
}
