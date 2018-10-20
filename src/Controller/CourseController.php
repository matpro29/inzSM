<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Section;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Form\Course\EnterForm;
use App\Form\Course\NewAdminForm;
use App\Form\Course\NewTeacherForm;
use App\Form\Course\SearchForm;
use App\Form\SectionNewFom;
use App\Repository\CourseRepository;
use App\Repository\SectionRepository;
use App\Repository\UserCourseRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        if ($this->security->isGranted('ROLE_TEACHER')) {
            $courses = $courseRepository->findAllByOwnerId($user->getId());
        } else {
            $courses = $courseRepository->findAllByUserId($user->getId());
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
    public function new(Request $request, UserInterface $user): Response
    {
        $course = new Course();

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
                $course->setOwner($this->getUser());
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

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="course_search", methods="GET|POST")
     */
    public function search(CourseRepository $courseRepository, Request $request): Response
    {
        $courseSearch = new Course();

        $form = $this->createForm(SearchForm::class, $courseSearch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $courseSearch->getName()) {
            return $this->render('course/search.html.twig', [
                'courses' => $courseRepository->findAllBySearchForm($courseSearch->getName()),
                'form' => $form->createView()
            ]);
        }

        $courses = $courseRepository->findAll();

        return $this->render('course/search.html.twig', [
            'courses' => $courses,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/section/new/{id}", name="course_section_new", methods="GET|POST")
     */
    public function sectionNew(Course $course, Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionNewFom::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setCourse($course);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('course_show', [
                'id' => $course->getId()
            ]);
        }

        return $this->render('course/section_new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods="GET|POST")
     */
    public function show(Course $course, Request $request, SectionRepository $sectionRepository, UserCourseRepository $userCourseRepository, UserInterface $user): Response
    {
        if ($user->getId() == $course->getOwner()->getId()
            || $userCourseRepository->getOneByCourseIdUserId($course->getId(), $user->getId())
            || $this->security->isGranted('ROLE_ADMIN')) {
            $sections = $sectionRepository->findAllByCourseId($course->getId());

            return $this->render('course/show.html.twig', [
                'course' => $course,
                'sections' => $sections
            ]);
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

                    return $this->render('course/show.html.twig', [
                        'course' => $course
                    ]);
                }
            }

            return $this->render('course/join.html.twig', [
                'course' => $course,
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/user/info/{id_course}/{id_user}", name="course_user_info")
     * @ParamConverter("course", options={"id": "id_course"})
     * @ParamConverter("user", options={"id": "id_user"})
     */
    public function userInfo(Course $course, User $user): Response
    {
        return $this->render('course/user_info.html.twig', [
            'course' => $course,
            'user' => $user
        ]);
    }

    /**
     * @Route("/users/{id}", name="course_users", methods="GET|POST")
     */
    public function users(Course $course, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllByCourseId($course->getId());

        return $this->render('course/users.html.twig', [
            'course' => $course,
            'users' => $users
        ]);
    }
}
