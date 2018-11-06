<?php

namespace App\Controller\Table;

use App\Entity\Grade;
use App\Form\Grade\NewForm;
use App\Repository\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grade")
 */
class GradeController extends Controller
{
    /**
     * @Route("/{id}", name="grade_delete", methods="DELETE")
     */
    public function delete(Grade $grade, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grade_index');
    }

    /**
     * @Route("/edit/{id}", name="grade_edit", methods="GET|POST")
     */
    public function edit(Grade $grade, Request $request): Response
    {
        $form = $this->createForm(NewForm::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $grade->getId()
            ];

            return $this->redirectToRoute('grade_edit', $params);
        }

        $user = $this->getUser();

        $params = [
            'form' => $form->createView(),
            'grade' => $grade,
            'user' => $user
        ];

        return $this->render('table/grade/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="grade_index", methods="GET")
     */
    public function index(GradeRepository $gradeRepository): Response
    {
        $grades = $gradeRepository->findAll();
        $user = $this->getUser();

        $params = [
            'grades' => $grades,
            'user' => $user
        ];

        return $this->render('table/grade/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="grade_info", methods="GET", requirements={"id"="\d+"})
     */
    public function info(Grade $grade): Response
    {
        $user = $this->getUser();

        $params = [
            'grade' => $grade,
            'user' => $user
        ];

        return $this->render('table/grade/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="grade_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $grade = new Grade();
        $form = $this->createForm(NewForm::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirectToRoute('grade_index');
        }

        $user = $this->getUser();

        $params = [
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('table/grade/new.html.twig', $params);
    }
}
