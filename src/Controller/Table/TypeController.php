<?php

namespace App\Controller\Table;

use App\Entity\Type;
use App\Form\Type\NewForm;
use App\Repository\NoticeRepository;
use App\Repository\TypeRepository;
use App\Service\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/table/type")
 */
class TypeController extends Controller
{
    private $security;
    private $parameter;

    public function __construct(NoticeRepository $noticeRepository, Security $security)
    {
        $this->security = $security;
        $this->parameter = new Parameter($noticeRepository, $security);
    }

    /**
     * @Route("/{id}", name="table_type_delete", methods="DELETE")
     */
    public function delete(Request $request, Type $type): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('table_type_index');
    }

    /**
     * @Route("/edit/{id}", name="table_type_edit", methods="GET|POST")
     */
    public function edit(Request $request, Type $type): Response
    {
        $form = $this->createForm(NewForm::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [
                'id' => $type->getId()
            ];

            return $this->redirectToRoute('table_type_edit', $params);
        }

        $params = [
            'form' => $form->createView(),
            'type' => $type
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/type/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="table_type_index", methods="GET")
     */
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();

        $params = [
            'types' => $types
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/type/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="table_type_info", methods="GET")
     */
    public function info(Type $type): Response
    {
        $params = [
            'type' => $type
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/type/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="table_type_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $type = new Type();
        $form = $this->createForm(NewForm::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('table_type_index');
        }

        $params = [
            'form' => $form->createView()
        ];

        $params = $this->parameter->getParams($this, $params);

        return $this->render('table/type/new.html.twig', $params);
    }
}
