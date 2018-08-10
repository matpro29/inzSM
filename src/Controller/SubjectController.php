<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\Subject\NewForm;
use App\Form\SubjectType;
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
            $em = $this->getDoctrine()->getManager();
            $em->remove($subject);
            $em->flush();
        }

        return $this->redirectToRoute('subject_index');
    }

    /**
     * @Route("/{id}/edit", name="subject_edit", methods="GET|POST")
     */
    public function edit(Request $request, Subject $subject)
    {
        $form = $this->createForm(NewForm::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subject_edit', [
                'id' => $subject->getId()
            ]);
        }

        return $this->render('subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="subject_index", methods="GET")
     */
    public function index(SubjectRepository $subjectRepository)
    {
        return $this->render('subject/index.html.twig', [
            'subjects' => $subjectRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="subject_new", methods="GET|POST")
     */
    public function new(Request $request)
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

        return $this->render('subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_show", methods="GET")
     */
    public function show(Subject $subject)
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject]
        );
    }
}
