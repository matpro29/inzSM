<?php

namespace App\Service;

use App\Repository\NoticeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

class Parameter extends Controller
{
    private $noticeRepository;
    private $security;
    private $userRepository;

    public function __construct(NoticeRepository $noticeRepository, Security $security, UserRepository $userRepository)
    {
        $this->noticeRepository = $noticeRepository;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function getCountNewNotices($params, $user)
    {
        if ($user && $this->security->isGranted('ROLE_TEACHER')) {
            $newAdminNotices = $this->noticeRepository->findNewAdminByUserId($user->getId(), $this->userRepository);
            $countNewAdminNotices = count($newAdminNotices);
            $params['countNewNotices'] = $countNewAdminNotices;
        } elseif ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findNewByUserId($user->getId());
            $newAdminNotices = $this->noticeRepository->findNewAdminByUserId($user->getId(), $this->userRepository);
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
        $user = $context->getUser();
        $params['user'] = $user;

        if ($user && $this->security->isGranted('ROLE_TEACHER')) {
            $newAdminNotices = $this->noticeRepository->findNewAdminByUserId($user->getId(), $this->userRepository);
            $countNewAdminNotices = count($newAdminNotices);
            $params['countNewNotices'] = $countNewAdminNotices;
        } elseif ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findNewByUserId($user->getId());
            $newAdminNotices = $this->noticeRepository->findNewAdminByUserId($user->getId(), $this->userRepository);
            $countNewNotices = count($newNotices);
            $countNewAdminNotices = count($newAdminNotices);
            $params['countNewNotices'] = $countNewNotices + $countNewAdminNotices;
        } else {
            $params['countNewNotices'] = null;
        }

        return $params;
    }
}
