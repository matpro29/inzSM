<?php

namespace App\Controller\Admin;

use App\Repository\NoticeRepository;
use App\Service\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/", name="admin_index")
     */
    public function index(): Response
    {
        $params = $this->parameter->getParams($this, []);

        return $this->render('admin/admin/index.html.twig', $params);
    }
}
