<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Section;
use App\Entity\User;
use App\Entity\UserCourseGrade;
use App\Entity\UserSectionGrade;
use App\Form\Grade\NewCourseForm;
use App\Form\Grade\NewSectionForm;
use App\Repository\NoticeRepository;
use App\Repository\UserCourseGradeRepository;
use App\Repository\UserCourseRepository;
use App\Repository\UserRepository;
use App\Repository\UserSectionGradeRepository;
use App\Service\Parameter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/user/grade")
 */
class GradeController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/end/{courseId}/{userId}", name="course_user_grade_end")
     * @ParamConverter("course", options={"id": "courseId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function end(Course $course, Request $request, User $userInfo, UserCourseRepository $userCourseRepository): Response
    {
        $userCourseGrade = new UserCourseGrade();

        $form = $this->createForm( NewCourseForm::class, $userCourseGrade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCourseGrade->setCourse($course);
            $userCourseGrade->setUser($userInfo);

            $dateTime = new \DateTime();
            $userCourse = $userCourseRepository->findOneByCourseIdUserId($course->getId(), $userInfo->getId());
            $userCourse = $userCourse[0];
            $userCourse->setEndDate($dateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userCourseGrade);
            $entityManager->flush();

            $params = [
                'courseId' => $course->getId(),
                'userId' => $userInfo->getId()
            ];

            return $this->redirectToRoute('course_user_grade', $params);
        }

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'userInfo' => $userInfo
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/grade/end.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="course_grade", methods="GET")
     */
    public function grade(Course $course, UserCourseGradeRepository $userCourseGradeRepository, UserSectionGradeRepository $userSectionGradeRepository): Response
    {
        $user = $this->getUser();

        $courseGrade = $userCourseGradeRepository->findOneByCourseIdUserId($course->getId(), $user->getId());
        $sectionsGrades = $userSectionGradeRepository->findAllByCourseIdUserId($course->getId(), $user->getId());
        if ($courseGrade && $courseGrade[0]) {
            $courseGrade = $courseGrade[0];
        } else {
            $courseGrade = null;
        }

        $params = [
            'course' => $course,
            'courseGrade' => $courseGrade,
            'sectionsGrades' => $sectionsGrades,
            'user' => $user
        ];

        $params = $this->parameter->getCountNewNotices($params, $user);

        return $this->render('course/grade/grade.html.twig', $params);
    }

    /**
     * @Route("/{courseId}/{userId}", name="course_user_grade")
     * @ParamConverter("course", options={"id": "courseId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function index(Course $course, User $userInfo): Response
    {
        $params = [
            'course' => $course,
            'userInfo' => $userInfo
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/grade/index.html.twig', $params);
    }

    /**
     * @Route("/new/{sectionId}/{userId}", name="course_user_grade_new")
     * @ParamConverter("section", options={"id": "sectionId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function new(Request $request, Section $section, User $userInfo): Response
    {
        $userSectionGrade = new UserSectionGrade();

        $form = $this->createForm(NewSectionForm::class, $userSectionGrade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userSectionGrade->setSection($section);
            $userSectionGrade->setUser($userInfo);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userSectionGrade);
            $entityManager->flush();

            $params = [
                'courseId' => $section->getCourse()->getId(),
                'userId' => $userInfo->getId()
            ];

            return $this->redirectToRoute('course_user_grade', $params);
        }

        $params = [
            'form' => $form->createView(),
            'section' => $section,
            'userInfo' => $userInfo
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/grade/new.html.twig', $params);
    }
}
