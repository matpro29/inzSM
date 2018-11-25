<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Section;
use App\Form\Section\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\FileRepository;
use App\Repository\NoticeRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\UserSectionGradeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
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

    public function __construct(ConversationRepository $conversationRepository,
                                NoticeRepository $noticeRepository,
                                Security $security,
                                UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }


    /**
     * @Route("/{id}", name="course_section_delete", methods="DELETE")
     */
    public function delete(FileRepository $fileRepository,
                           Request $request,
                           Section $section,
                           TaskRepository $taskRepository,
                           UserSectionGradeRepository $userSectionGradeRepository): Response
    {
        $course = $section->getCourse();

        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $fileSystem = new FileSystem();
            $entityManager = $this->getDoctrine()->getManager();

            $tasks = $taskRepository->findAllBySectionId($section->getId());

            foreach ($tasks as $task) {
                $files = $fileRepository->findAllByTaskId($task->getId());

                foreach ($files as $file) {
                    $targetFile = $this->getParameter('files_directory') . '/' . $file->getFile();

                    $fileSystem->remove($targetFile);

                    $entityManager->remove($file);
                }

                $entityManager->remove($task);
            }

            $sectionGrades = $userSectionGradeRepository->findAllBySectionId($section->getId());

            foreach ($sectionGrades as $sectionGrade) {
                $entityManager->remove($sectionGrade);
            }

            $entityManager->remove($section);
            $entityManager->flush();
        }

        $params = [
            'id' => $course->getId()
        ];

        return $this->redirectToRoute('course_show', $params);
    }

    /**
     * @Route("/edit/{id}", name="course_section_edit", methods="GET|POST")
     */
    public function edit(Request $request,
                         Section $section): Response
    {
        $form = $this->createForm(NewForm::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $section->getCourse()->getId()
            ];

            return $this->redirectToRoute('course_show', $params);
        }

        $params = [
            'form' => $form->createView(),
            'section' => $section
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/section/edit.html.twig', $params);
    }

    /**
     * @Route("/new/{id}", name="course_section_new", methods="GET|POST")
     */
    public function new(Course $course,
                        Request $request): Response
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
