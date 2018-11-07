<?php

namespace App\Controller\Course;

use App\Entity\File;
use App\Entity\Task;
use App\Form\File\NewForm;
use App\Repository\NoticeRepository;
use App\Service\FileUploader;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/task/file")
 */
class FileController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/{id}", name="course_file_delete", methods="DELETE")
     */
    public function delete(Request $request, File $file): Response
    {
        $course = $file->getTask()->getSection()->getCourse();

        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $fileSystem = new FileSystem();
            $targetFile = $this->getParameter('files_directory') . '/' . $file->getFile();

            $fileSystem->remove($targetFile);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager ->remove($file);
            $entityManager ->flush();
        }

        $params = [
            'id' => $course->getId()
        ];

        return $this->redirectToRoute('course_show', $params);
    }

    /**
     * @Route("/new/{id}", name="course_file_new", methods="GET|POST")
     */
    public function new(Request $request, Task $task): Response
    {
        $file = new File();
        $form = $this->createForm(NewForm::class, $file);
        $form->handleRequest($request);

        $course = $task->getSection()->getCourse();

        if ($form->isSubmitted() && $form->isValid()) {
            $targetDirectory = $this->getParameter('files_directory');
            $fileUploader = new FileUploader($targetDirectory);

            $fileName = $fileUploader->upload($file->getFile());
            $user = $this->getUser();

            $file->setFile($fileName);
            $file->setTask($task);
            $file->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
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

        return $this->render('course/file/new.html.twig', $params);
    }
}