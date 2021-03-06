<?php

namespace App\Service;

use App\Entity\UserConversation;
use App\Repository\ConversationRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

class Parameter extends Controller
{
    private $conversationRepository;
    private $noticeRepository;
    private $security;
    private $userRepository;

    public function __construct(ConversationRepository $conversationRepository, NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->conversationRepository = $conversationRepository;
        $this->noticeRepository = $noticeRepository;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function getParams($context, $params)
    {
        $countNewCourseNotices = 0;

        $user = $context && $context->getUser() ? $context->getUser() : null;

        if (!$user) {
            $params['countNewConversations'] = 0;
            $params['countNewNotices'] = 0;
            $params['user'] = null;

            return $params;
        }

        $newAdminNotices = $this->noticeRepository->findAllNewAdminByUserId($user->getId(), $this->userRepository);
        $countNewAdminNotices = count($newAdminNotices);

        if ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findAllNewByUserId($user->getId());
            $countNewCourseNotices = count($newNotices);
        }

        $newConversations = $this->conversationRepository->findAllNewByUserId($user->getId());
        $countNewConversations = count($newConversations);

        $params['countNewConversations'] = $countNewConversations;
        $params['countNewNotices'] = $countNewCourseNotices + $countNewAdminNotices;
        $params['user'] = $user;

        return $params;
    }
}
