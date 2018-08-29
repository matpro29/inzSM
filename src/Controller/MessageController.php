<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\UserMessage;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/message")
 */
class MessageController extends Controller
{
    /**
     * @Route("/", name="message_index", methods="GET")
     */
    public function index(UserInterface $user, UserMessageRepository $userMessageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'userMessages' => $userMessageRepository->findAllByUserId($user->getId())
        ]);
    }

    /**
     * @Route("/{id}", name="message_show", methods="GET")
     */
    public function show(MessageRepository $messageRepository, UserMessage $userMessage): Response
    {
        return $this->render('message/show.html.twig', [
            'messages' => $messageRepository->findAllByUserMessageId($userMessage->getId())
        ]);
    }
}
