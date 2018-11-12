<?php

namespace App\Controller\Table;

use App\Entity\Subject;
use App\Form\Subject\NewForm;
use App\Repository\NoticeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/table/subject")
 */
class SubjectController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/{id}", name="table_subject_delete", methods="DELETE")
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('table_subject_index');
    }

    /**
     * @Route("/edit/{id}", name="table_subject_edit", methods="GET|POST")
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

            return $this->redirectToRoute('table_subject_edit', $params);
        }

        $params = [
            'form' => $form->createView(),
            'subject' => $subject
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/subject/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="table_subject_index", methods="GET")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findAll();

        $params = [
            'subjects' => $subjects
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/subject/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="table_subject_info", methods="GET")
     */
    public function info(Subject $subject): Response
    {
        $params = [
            'subject' => $subject
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/subject/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="table_subject_new", methods="GET|POST")
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

            return $this->redirectToRoute('table_subject_index');
        }

        $params = [
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/subject/new.html.twig', $params);
    }
}
