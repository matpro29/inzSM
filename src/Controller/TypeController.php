<?php

namespace App\Controller;

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
            $em = $this->getDoctrine()->getManager();
            $em->remove($type);
            $em->flush();
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

            return $this->redirectToRoute('type_edit', [
                'id' => $type->getId()
            ]);
        }

        return $this->render('type/edit.html.twig', [
            'form' => $form->createView(),
            'type' => $type

        ]);
    }

    /**
     * @Route("/", name="type_index", methods="GET")
     */
    public function index(TypeRepository $typeRepository): Response
    {
        return $this->render('type/index.html.twig', [
            'types' => $typeRepository->findAll()
        ]);
    }

    /**
     * @Route("/info/{id}", name="type_info", methods="GET")
     */
    public function info(Type $type): Response
    {
        return $this->render('info.html.twig', [
            'type' => $type
        ]);
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

        return $this->render('type/new.html.twig', [
            'form' => $form->createView(),
            'type' => $type

        ]);
    }
}
