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

    public function getCountNewNotices($params, $user)
    {
        if ($user && $this->security->isGranted('ROLE_TEACHER')) {
            $newAdminNotices = $this->noticeRepository->findAllNewAdminByUserId($user->getId(), $this->userRepository);
            $countNewAdminNotices = count($newAdminNotices);
            $params['countNewNotices'] = $countNewAdminNotices;
        } elseif ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findAllNewByUserId($user->getId());
            $newAdminNotices = $this->noticeRepository->findAllNewAdminByUserId($user->getId(), $this->userRepository);
            $countNewNotices = count($newNotices);
            $countNewAdminNotices = count($newAdminNotices);
            $params['countNewNotices'] = $countNewNotices + $countNewAdminNotices;
        } else {
            $params['countNewNotices'] = null;
        }

        return $params;
    }

    public function getParams($context, $params)
    {
        $countNewNotices = 0;

        $user = $context->getUser();

        $newAdminNotices = $this->noticeRepository->findAllNewAdminByUserId($user->getId(), $this->userRepository);
        $countNewAdminNotices = count($newAdminNotices);

        if ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findAllNewByUserId($user->getId());
            $countNewNotices = count($newNotices);
        }

        $newConversations = $this->conversationRepository->findAllNewByUserId($user->getId());
        $countNewConversations = count($newConversations);

        $params['countNewConversations'] = $countNewConversations;
        $params['countNewNotices'] = $countNewNotices + $countNewAdminNotices;
        $params['user'] = $user;

        return $params;
    }
}
