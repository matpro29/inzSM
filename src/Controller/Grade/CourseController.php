<?php

namespace App\Controller\Grade;

use App\Repository\CourseRepository;
use App\Repository\NoticeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/grade")
 */
class CourseController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/", name="grade_index", methods="GET")
     */
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = null;
        $user = $this->getUser();

        $courses = $courseRepository->findAllByUserId($user->getId());

        $params = [
            'user' => $user,
            'courses' => $courses
        ];

        $params = $this->parameter->getCountNewNotices($params, $user);

        return $this->render('grade/course/index.html.twig', $params);
    }
}
