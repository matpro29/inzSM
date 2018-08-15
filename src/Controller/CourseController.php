<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\Course\NewAdminForm;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/course")
 */
class CourseController extends Controller
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/{id}", name="course_delete", methods="DELETE")
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }

    /**
     * @Route("/edit/{id}", name="course_edit", methods="GET|POST")
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(NewAdminForm::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_edit', [
                'id' => $course->getId()
            ]);
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="course_index", methods="GET")
     */
    public function index(CourseRepository $courseRepository, UserInterface $user): Response
    {
        $courses = null;

        if($this->security->isGranted('ROLE_USER')) {
            $courses = $courseRepository->findAllByUserId($user->getId());
        } elseif($this->security->isGranted('ROLE_TEACHER')) {
            $courses = $courseRepository->findAllByOwnerId($user->getId());
        }

        return $this->render('course/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/info/{id}", name="course_info", methods="GET")
     */
    public function info(Course $course): Response
    {
        return $this->render('course/info.html.twig', [
            'course' => $course
        ]);
    }

    /**
     * @Route("/new", name="course_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(NewAdminForm::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setPassword($course->getPlainPassword());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="course_search", methods="GET")
     */
    public function search(CourseRepository $courseRepository)
    {
        return $this->render('course/search.html.twig', [
            'courses' => $courseRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods="GET")
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course
        ]);
    }
}
