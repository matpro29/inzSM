<?php

namespace App\Controller\Course;

use App\Entity\File;
use App\Entity\Task;
use App\Form\File\NewForm;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/course/task/file")
 */
class FileController extends Controller
{
    /**
     * @Route("/new/{id}", name="course_file_new", methods="GET|POST")
     */
    public function new(Request $request, Task $task, UserInterface $user): Response
    {
        $file = new File();
        $form = $this->createForm(NewForm::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $targetDirectory = $this->getParameter('files_directory');
            $fileUploader = new FileUploader($targetDirectory);

            $fileName = $fileUploader->upload($file->getFile());

            $file->setFile($fileName);
            $file->setTask($task);
            $file->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            return $this->redirectToRoute('course_show', [
                'id' => $task->getSection()->getCourse()->getId()
            ]);
        }

        return $this->render('course/file/new.html.twig', [
            'course' => $task->getSection()->getCourse(),
            'form' => $form->createView()
        ]);
    }
}