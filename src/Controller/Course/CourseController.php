<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\UserCourse;
use App\Form\Course\EnterForm;
use App\Form\Course\NewAdminForm;
use App\Form\Course\NewTeacherForm;
use App\Form\Course\SearchForm;
use App\Repository\CourseRepository;
use App\Repository\UserCourseRepository;
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

            $params = [
                'id' => $course->getId()
            ];

            return $this->redirectToRoute('course_edit', $params);
        }

        $user = $this->getUser();

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('course/course/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="course_index", methods="GET")
     */
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = null;
        $user = $this->getUser();

        if ($this->security->isGranted('ROLE_TEACHER')) {
            $courses = $courseRepository->findAllByOwnerId($user->getId());
        } else {
            $courses = $courseRepository->findAllByUserId($user->getId());
        }

        $params = [
            'courses' => $courses,
            'user' => $user
        ];

        return $this->render('course/course/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="course_info", methods="GET")
     */
    public function info(Course $course): Response
    {
        $user = $this->getUser();

        $params = [
            'course' => $course,
            'user' => $user
        ];

        return $this->render('course/course/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="course_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $user = $this->getUser();

        $form = null;
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(NewAdminForm::class, $course);
        } else {
            $form = $this->createForm(NewTeacherForm::class, $course);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = password_hash($course->getPlainPassword(), PASSWORD_BCRYPT, [
                'cost' => 13
            ]);
            $course->setPassword($password);

            if (!$this->security->isGranted('ROLE_ADMIN')) {
                $course->setOwner($user);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            if ($this->security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('course_search');
            } else {
                return $this->redirectToRoute('course_index');
            }
        }

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('course/course/new.html.twig', $params);
    }

    /**
     * @Route("/search", name="course_search", methods="GET|POST")
     */
    public function search(CourseRepository $courseRepository, Request $request): Response
    {
        $courseSearch = new Course();

        $form = $this->createForm(SearchForm::class, $courseSearch);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid() && $courseSearch->getName()) {
            $courses = $courseRepository->findAllBySearchForm($courseSearch->getName());

            $params = [
                'courses' => $courses,
                'form' => $form->createView(),
                'user' => $user
            ];

            return $this->render('course/course/search.html.twig', $params);
        }

        $courses = $courseRepository->findAll();

        $params = [
            'courses' => $courses,
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('course/course/search.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="course_show", methods="GET|POST")
     */
    public function show(Course $course, Request $request, UserCourseRepository $userCourseRepository): Response
    {
        $user = $this->getUser();

        $params = [
            'course' => $course,
            'user' => $user
        ];

        if ($user->getId() == $course->getOwner()->getId()
            || $userCourseRepository->getOneByCourseIdUserId($course->getId(), $user->getId())
            || $this->security->isGranted('ROLE_ADMIN')) {

            return $this->render('course/course/show.html.twig', $params);
        } else {
            $form = $this->createForm(EnterForm::class, $course);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (password_verify($course->getPlainPassword(), $course->getPassword())) {
                    $userCourse = new UserCourse();
                    $userCourse->setCourse($course);
                    $userCourse->setStatus('Trwa');
                    $userCourse->setUser($user);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($userCourse);
                    $entityManager->flush();

                    return $this->render('course/course/show.html.twig', $params);
                }
            }

            $params['form'] = $form->createView();

            return $this->render('course/course/join.html.twig', $params);
        }
    }
}
