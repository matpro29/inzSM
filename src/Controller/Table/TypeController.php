<?php

namespace App\Controller\Table;

use App\Entity\Type;
use App\Form\Type\NewForm;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type")
 */
class TypeController extends Controller
{
    /**
     * @Route("/{id}", name="type_delete", methods="DELETE")
     */
    public function delete(Request $request, Type $type): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_index');
    }

    /**
     * @Route("/edit/{id}", name="type_edit", methods="GET|POST")
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

            return $this->redirectToRoute('type_edit', $params);
        }

        $user = $this->getUser();

        $params = [
            'form' => $form->createView(),
            'type' => $type,
            'user' => $user
        ];

        return $this->render('table/type/edit.html.twig', $params);
    }

    /**
     * @Route("/", name="type_index", methods="GET")
     */
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();
        $user = $this->getUser();

        $params = [
            'types' => $types,
            'user' => $user
        ];

        return $this->render('table/type/index.html.twig', $params);
    }

    /**
     * @Route("/info/{id}", name="type_info", methods="GET")
     */
    public function info(Type $type): Response
    {
        $user = $this->getUser();

        $params = [
            'type' => $type,
            'user' => $user
        ];

        return $this->render('table/type/info.html.twig', $params);
    }

    /**
     * @Route("/new", name="type_new", methods="GET|POST")
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

            return $this->redirectToRoute('type_index');
        }

        $user = $this->getUser();

        $params = [
            'form' => $form->createView(),
            'user' => $user
        ];

        return $this->render('table/type/new.html.twig', $params);
    }
}
