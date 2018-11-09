<?php

namespace App\Service;

use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

class Parameter extends Controller
{
    private $noticeRepository;
    private $security;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->noticeRepository = $noticeRepository;
        $this->security = $security;
    }

    public function getParams($context, $params)
    {
        $user = $context->getUser();
        $params['user'] = $user;

        if ($user && $this->security->isGranted('ROLE_USER')) {
            $newNotices = $this->noticeRepository->findNewByUserId($user->getId());
            $countNewNotices = count($newNotices);
            $params['countNewNotices'] = $countNewNotices;
        } else {
            $params['countNewNotices'] = null;
        }

        return $params;
    }
}
