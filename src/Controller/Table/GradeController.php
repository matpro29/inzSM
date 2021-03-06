<?php

namespace App\Controller\Table;

use App\Entity\Grade;
use App\Form\Grade\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\GradeRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/table/grade")
 */
class GradeController extends Controller
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
     * @Route("/{id}", name="table_grade_delete", methods="DELETE")
     */
    public function delete(Grade $grade,
                           Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('table_grade_index');
    }

    /**
     * @Route("/edit/{id}", name="table_grade_edit", methods="GET|POST")
     */
    public function edit(Grade $grade,
                         Request $request): Response
    {
        $form = $this->createForm(NewForm::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $grade->getId()
            ];

            return $this->redirectToRoute('table_grade_edit', $params);
        }

        $params = [
            'form' => $form->createView(),
            'grade' => $grade
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/grade/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="table_grade_index", methods="GET")
     */
    public function index(GradeRepository $gradeRepository): Response
    {
        $grades = $gradeRepository->findAll();

        $params = [
            'grades' => $grades
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/grade/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="table_grade_info", methods="GET", requirements={"id"="\d+"})
     */
    public function info(Grade $grade): Response
    {
        $params = [
            'grade' => $grade
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/grade/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="table_grade_new", methods="GET|POST")
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

            return $this->redirectToRoute('table_grade_index');
        }

        $params = [
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/grade/new.html.twig', $params);
    }
}
