<?php

namespace App\Controller;

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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/conversation")
 */
class ConversationController extends Controller
{
    /**
     * @Route("/", name="conversation_index", methods="GET")
     */
    public function index(ConversationRepository $conversationRepository, UserInterface $user): Response
    {
        $conversations = $conversationRepository->findAllByUserId($user->getId());

        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations
        ]);
    }

    /**
     * @Route("/{id}", name="conversation_show", methods="GET|POST")
     */
    public function show(Conversation $conversation, MessageRepository $messageRepository, Request $request, UserInterface $user): Response
    {
        $message = new Message();
        $form = $this->createForm(NewForm::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setOwner($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
        }

        $messages = $messageRepository->findAllByConversationId($conversation->getId());
        $form = $form->createView();

        return $this->render('conversation/show.html.twig', [
            'form' => $form,
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/", name="conversation_new", methods="NEW")
     */
    public function new(MessageRepository $messageRepository, UserInterface $user): Response
    {
        $conversation = new Conversation();
        $userConversation = new UserConversation();
        $userConversation->setConversation($conversation);
        $userConversation->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversation);
        $entityManager->persist($userConversation);
        $entityManager->flush();

        $conversation_id = $conversation->getId();
        $messages = $messageRepository->findAllByConversationId($conversation_id);

        return $this->redirectToRoute('conversation_show', [
            'id' => $conversation_id,
            'messages' => $messages
        ]);
    }
}
