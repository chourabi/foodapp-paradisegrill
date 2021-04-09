<?php

namespace App\Controller;

use App\Entity\ItemsToOptions;
use App\Form\ItemsToOptionsType;
use App\Repository\ItemsToOptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/items/to/options")
 */
class ItemsToOptionsController extends AbstractController
{
    /**
     * @Route("/", name="items_to_options_index", methods={"GET"})
     */
    public function index(ItemsToOptionsRepository $itemsToOptionsRepository): Response
    {
        return $this->render('items_to_options/index.html.twig', [
            'items_to_options' => $itemsToOptionsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="items_to_options_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $itemsToOption = new ItemsToOptions();
        $form = $this->createForm(ItemsToOptionsType::class, $itemsToOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemsToOption);
            $entityManager->flush();

            return $this->redirectToRoute('items_to_options_index');
        }

        return $this->render('items_to_options/new.html.twig', [
            'items_to_option' => $itemsToOption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="items_to_options_show", methods={"GET"})
     */
    public function show(ItemsToOptions $itemsToOption): Response
    {
        return $this->render('items_to_options/show.html.twig', [
            'items_to_option' => $itemsToOption,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="items_to_options_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ItemsToOptions $itemsToOption): Response
    {
        $form = $this->createForm(ItemsToOptionsType::class, $itemsToOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('items_to_options_index');
        }

        return $this->render('items_to_options/edit.html.twig', [
            'items_to_option' => $itemsToOption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="items_to_options_delete", methods={"POST"})
     */
    public function delete(Request $request, ItemsToOptions $itemsToOption): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itemsToOption->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($itemsToOption);
            $entityManager->flush();
        }

        return $this->redirect($request->request->get('referer'));
    }
}
