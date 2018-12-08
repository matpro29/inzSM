<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\UserConversation;
use App\Form\Message\NewForm;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserConversationRepository;
use App\Repository\UserRepository;
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

    public function __construct(ConversationRepository $conversationRepository,
                                NoticeRepository $noticeRepository,
                                Security $security,
                                UserRepository $userRepository)
    {
        $this->security = $security;
        $this->parameter = new Parameter($conversationRepository, $noticeRepository, $security, $userRepository);
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
    public function show(Conversation $conversation,
                         MessageRepository $messageRepository,
                         Request $request,
                         UserConversationRepository $userConversationRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(NewForm::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setOwner($this->getUser());

            $date = new \DateTime();
            $message->setDate($date);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
        }

        $messages = $messageRepository->findAllByConversationId($conversation->getId());

        $params = [
            'conversation' => $conversation,
            'form' => $form->createView(),
            'messages' => $messages
        ];

        $params = $this->parameter->getParams($this, $params);
        
        $userConversation = $userConversationRepository->findOneByConversationIdUserId($conversation->getId(), $params['user']->getId());
        
        $conversationDate = new \DateTime();
        $userConversation->setConversationDate($conversationDate);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

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

        $conversationDate = new \DateTime();
        $userConversation->setConversationDate($conversationDate);

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
