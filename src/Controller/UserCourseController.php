<?php

namespace App\Controller;

use App\Entity\UserCourse;
use App\Form\UserCourse\NewUserCourse;
use App\Repository\UserCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/course")
 */
class UserCourseController extends Controller
{
    /**
     * @Route("/{id}", name="user_course_delete", methods="DELETE")
     */
    public function delete(Request $request, UserCourse $userCourse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userCourse->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userCourse);
            $em->flush();
        }

        return $this->redirectToRoute('user_course_index');
    }

    /**
     * @Route("/edit/{id}", name="user_course_edit", methods="GET|POST")
     */
    public function edit(Request $request, UserCourse $userCourse): Response
    {
        $form = $this->createForm(NewUserCourse::class, $userCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_course_edit', ['id' => $userCourse->getId()]);
        }

        return $this->render('user_course/edit.html.twig', [
            'form' => $form->createView(),
            'user_course' => $userCourse
        ]);
    }

    /**
     * @Route("/", name="user_course_index", methods="GET")
     */
    public function index(UserCourseRepository $userCourseRepository): Response
    {
        return $this->render('user_course/index.html.twig', [
            'user_courses' => $userCourseRepository->findAll()
        ]);
    }

    /**
     * @Route("/info/{id}", name="user_course_info", methods="GET")
     */
    public function info(UserCourse $userCourse): Response
    {
        return $this->render('user_course/info.html.twig', [
            'user_course' => $userCourse
        ]);
    }

    /**
     * @Route("/new", name="user_course_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $userCourse = new UserCourse();
        $form = $this->createForm(NewUserCourse::class, $userCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCourse);
            $em->flush();

            return $this->redirectToRoute('user_course_index');
        }

        return $this->render('user_course/new.html.twig', [
            'form' => $form->createView(),
            'user_course' => $userCourse
        ]);
    }
}
