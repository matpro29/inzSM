<?php

namespace App\Controller\Grade;

use App\Entity\Course;
use App\Repository\NoticeRepository;
use App\Repository\UserCourseGradeRepository;
use App\Repository\UserSectionGradeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/grade/course")
 */
class GradeController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/{id}", name="grade_course_index", methods="GET")
     */
    public function index(Course $course, UserCourseGradeRepository $userCourseGradeRepository, UserSectionGradeRepository $userSectionGradeRepository): Response
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
            'courseGrade' => $courseGrade,
            'sectionsGrades' => $sectionsGrades,
            'user' => $user
        ];

        $params = $this->parameter->getCountNewNotices($params, $user);

        return $this->render('grade/grade/index.html.twig', $params);
    }
}
