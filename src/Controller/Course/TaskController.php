<?php

namespace App\Controller\Course;

use App\Entity\Section;
use App\Entity\Task;
use App\Form\Task\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\FileRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/section/task")
 */
class TaskController extends Controller
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
     * @Route("/{id}", name="course_task_delete", methods="DELETE")
     */
    public function delete(FileRepository $fileRepository,
                           Request $request,
                           Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $fileSystem = new FileSystem();
            $entityManager = $this->getDoctrine()->getManager();

            $files = $fileRepository->findAllByTaskId($task->getId());

            foreach ($files as $file) {
                $targetFile = $this->getParameter('files_directory') . '/' . $file->getFile();

                $fileSystem->remove($targetFile);

                $entityManager->remove($file);
            }


            $entityManager->remove($task);
            $entityManager->flush();
        }

        $params = [
            'id' => $task->getSection()->getCourse()->getId()
        ];

        return $this->redirectToRoute('course_show', $params);
    }

    /**
     * @Route("/edit/{id}", name="course_task_edit", methods="GET|POST")
     */
    public function edit(Request $request,
                         Task $task): Response
    {
        $form = $this->createForm(NewForm::class, $task);
        $form->handleRequest($request);

        $course = $task->getSection()->getCourse();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $course->getId()
            ];

            return $this->redirectToRoute('course_show', $params);
        }

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'task' => $task
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/task/edit.html.twig', $params);
    }

    /**
     * @Route("/new/{id}", name="course_task_new", methods="GET|POST")
     */
    public function new(Request $request,
                        Section $section): Response
    {
        $task = new Task();
        $form = $this->createForm(NewForm::class, $task);
        $form->handleRequest($request);

        $course = $section->getCourse();

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setSection($section);

            $endDate = new \DateTime($task->getEndDateString());
            $startDate = new \DateTime($task->getStartDateString());

            $task->setEndDate($endDate);
            $task->setStartDate($startDate);

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

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/task/new.html.twig', $params);
    }
}