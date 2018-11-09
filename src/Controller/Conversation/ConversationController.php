<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\UserConversation;
use App\Form\Message\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\NoticeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/conversation")
 */
class ConversationController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/", name="conversation_index", methods="GET")
     */
    public function index(ConversationRepository $conversationRepository): Response
    {
        $params = $this->parameter->getParams($this, []);
        $params['conversations'] = $conversationRepository->findAllByUserId($params['user']->getId());

        return $this->render('conversation/conversation/index.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="conversation_show", methods="GET|POST")
     */
    public function show(Conversation $conversation, MessageRepository $messageRepository, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(NewForm::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setOwner($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
        }

        $messages = $messageRepository->findAllByConversationId($conversation->getId());

        $params = [
            'form' => $form->createView(),
            'messages' => $messages
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('conversation/conversation/show.html.twig', $params);
    }

    /**
     * @Route("/", name="conversation_new", methods="NEW")
     */
    public function new(): Response
    {
        $conversation = new Conversation();
        $user = $this->getUser();
        $userConversation = new UserConversation();
        $userConversation->setConversation($conversation);
        $userConversation->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversation);
        $entityManager->persist($userConversation);
        $entityManager->flush();

        $params = [
            'id' => $conversation->getId()
        ];

        return $this->redirectToRoute('conversation_show', $params);
    }
}
