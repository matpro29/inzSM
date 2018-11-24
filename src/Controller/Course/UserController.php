<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/course/user")
 */
class UserController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
    }

    /**
     * @Route("/{id}", name="course_user", methods="GET|POST")
     */
    public function index(Course $course, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllByCourseId($course->getId());

        $params = [
            'course' => $course,
            'users' => $users
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('course/user/index.html.twig', $params);
    }
}
