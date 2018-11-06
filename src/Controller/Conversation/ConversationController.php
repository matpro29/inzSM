<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\UserConversation;
use App\Form\Message\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conversation")
 */
class ConversationController extends Controller
{
    /**
     * @Route("/", name="conversation_index", methods="GET")
     */
    public function index(ConversationRepository $conversationRepository): Response
    {
        $user = $this->getUser();
        $conversations = $conversationRepository->findAllByUserId($user->getId());

        $params = [
            'conversations' => $conversations,
            'user' => $user
        ];

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
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setOwner($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
        }

        $messages = $messageRepository->findAllByConversationId($conversation->getId());

        $params = [
            'form' => $form->createView(),
            'messages' => $messages
        ];

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
