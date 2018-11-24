<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\Section;
use App\Entity\User;
use App\Entity\UserCourseGrade;
use App\Entity\UserSectionGrade;
use App\Form\Grade\NewCourseForm;
use App\Form\Grade\NewSectionForm;
use App\Repository\ConversationRepository;
use App\Repository\GradeRepository;
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

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/end/{courseId}/{userId}", name="course_user_grade_end")
     * @ParamConverter("course", options={"id": "courseId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function end(Course $course, GradeRepository $gradeRepository, Request $request, User $userInfo, UserCourseRepository $userCourseRepository, UserSectionGradeRepository $userSectionGradeRepository): Response
    {
        $userCourseGrade = new UserCourseGrade();

        $endGrade =  $this->getEndGrade($course, $gradeRepository, $userInfo, $userSectionGradeRepository);
        if ($endGrade) {
            $userCourseGrade->setGrade($endGrade);
        }

        $form = $this->createForm( NewCourseForm::class, $userCourseGrade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCourseGrade->setCourse($course);
            $userCourseGrade->setUser($userInfo);

            $dateTime = new \DateTime();
            $userCourse = $userCourseRepository->findOneByCourseIdUserId($course->getId(), $userInfo->getId());
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

    private function getEndGrade(Course $course, GradeRepository $gradeRepository, User $userInfo, UserSectionGradeRepository $userSectionGradeRepository)
    {
        $sectionGrades = $userSectionGradeRepository->findAllByCourseIdUserId($course->getId(), $userInfo->getId());

        if (empty($sectionGrades)) {
            return null;
        }

        $sum= 0;
        $weightSum = 0;

        foreach ($sectionGrades as $sectionGrade) {
            $weight= $sectionGrade->getSection()->getWeight();
            $grade = $sectionGrade->getGrade()->getGrade();

            $sum += $grade*$weight;
            $weightSum += $weight;
        }

        $endGrade = $sum/$weightSum;

        if ($endGrade >= 4.75) {
            $endGrade = 5;
        } elseif ($endGrade >= 4.25) {
            $endGrade = 4.5;
        } elseif ($endGrade >= 3.75) {
            $endGrade = 4;
        } elseif ($endGrade >= 3.25) {
            $endGrade = 3.5;
        } elseif ($endGrade >= 2.75) {
            $endGrade = 3;
        } else {
            $endGrade = 2;
        }

        $endGrade = $gradeRepository->findOneBy([
            'grade' => $endGrade
        ]);

        return $endGrade;
    }

    /**
     * @Route("/{id}", name="course_grade", methods="GET")
     */
    public function grade(Course $course, UserCourseGradeRepository $userCourseGradeRepository, UserSectionGradeRepository $userSectionGradeRepository): Response
    {
        $user = $this->getUser();

        $courseGrade = $userCourseGradeRepository->findOneByCourseIdUserId($course->getId(), $user->getId());
        $sectionsGrades = $userSectionGradeRepository->findAllByCourseIdUserId($course->getId(), $user->getId());

        $params = [
            'course' => $course,
            'courseGrade' => $courseGrade,
            'sectionsGrades' => $sectionsGrades
        ];

        $params = $this->parameter->getParams($this, $params);

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
