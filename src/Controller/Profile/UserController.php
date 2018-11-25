<?php

namespace App\Controller\Profile;

use App\Form\User\ChangePasswordForm;
use App\Form\User\EditForm;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/profile")
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
     * @Route("/edit", name="profile_edit")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        $passwordForm = $this->createForm(ChangePasswordForm::class, $user);
        $passwordForm->handleRequest($request);

        $editForm = $this->createForm(EditForm::class, $user);
        $editForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            if (password_verify($user->getOldPassword(), $user->getPassword())) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('profile_index', []);
            }
            return $this->redirectToRoute('profile_edit', []);
        } else if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_index', []);
        }

        $params = [
            'editForm' => $editForm->createView(),
            'passwordForm' => $passwordForm->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('profile/user/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="profile_index")
     */
    public function index(): Response
    {
        $params = $this->parameter->getParams($this, []);

        return $this->render('profile/user/index.html.twig', $params);
    }
}
