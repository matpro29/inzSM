<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/info/{id_course}/{id_user}", name="course_user_info")
     * @ParamConverter("course", options={"id": "id_course"})
     * @ParamConverter("user", options={"id": "id_user"})
     */
    public function info(Course $course, User $userInfo): Response
    {
        $user = $this->getUser();

        $params = [
            'course' => $course,
            'user' => $user,
            'userInfo' => $userInfo
        ];

        return $this->render('course/user/info.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="course_user", methods="GET|POST")
     */
    public function index(Course $course, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $users = $userRepository->findAllByCourseId($course->getId());

        $params = [
            'course' => $course,
            'user' => $user,
            'users' => $users
        ];

        return $this->render('course/user/index.html.twig', $params);
    }
}
