<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\User;
use App\Entity\UserConversation;
use App\Form\User\SearchForm;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use App\Service\Parameter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/coversation/user")
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
     * @Route("/add/{conversationId}/{userId}", name="conversation_user_add", methods="GET")
     * @ParamConverter("conversation", options={"id": "conversationId"})
     * @ParamConverter("userInfo", options={"id": "userId"})
     */
    public function add(Conversation $conversation, User $userInfo)
    {
        $userConversation = new UserConversation();

        $userConversation->setUser($userInfo);
        $userConversation->setConversation($conversation);

        $conversationDate = new \DateTime();
        $userConversation->setConversationDate($conversationDate);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userConversation);
        $entityManager->flush();

        $params = [
            'id' => $conversation->getId()
        ];

        return $this->redirectToRoute('conversation_user_search', $params);
    }

    /**
     * @Route("/{id}", name="conversation_user_index", methods="GET")
     */
    public function index(Conversation $conversation, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllByConversationId($conversation->getId());

        $params = [
            'conversation' => $conversation,
            'users' => $users
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('conversation/user/index.html.twig', $params);
    }

    /**
     * @Route("/search/{id}", name="conversation_user_search", methods="GET|POST")
     */
    public function search(Conversation $conversation, Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(SearchForm::class, $user);
        $form->handleRequest($request);

        $users = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository->findAllBySearchForm($user->getUsername());
        } else {
            $users = $userRepository->findAll();
        }

        $params = [
            'conversation' => $conversation,
            'form' => $form->createView(),
            'users' => $users
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('conversation/user/search.html.twig', $params);
    }
}
