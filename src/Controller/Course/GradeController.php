<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserSectionGrade;
use App\Form\Grade\NewSectionForm;
use App\Repository\NoticeRepository;
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

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
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
     * @Route("/new/{courseId}/{userId}", name="course_user_grade_new")
     * @ParamConverter("course", options={"id": "courseId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function new(Course $course, Request $request, User $userInfo): Response
    {
        $userSectionGrade = new UserSectionGrade();

        $form = $this->createForm(NewSectionForm::class, $userSectionGrade , ['userId' => $userInfo->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        $params = [
            'course' => $course,
            'form' => $form->createView(),
            'userInfo' => $userInfo
        ];

        return $this->render('course/grade/new.html.twig', $params);
    }
}
