<?php

namespace App\Controller;

use App\Entity\OptionItems;
use App\Form\OptionItemsType;
use App\Repository\OptionItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/option/items")
 */
class OptionItemsController extends AbstractController
{
    /**
     * @Route("/", name="option_items_index", methods={"GET"})
     */
    public function index(OptionItemsRepository $optionItemsRepository): Response
    {
        return $this->render('option_items/index.html.twig', [
            'option_items' => $optionItemsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="option_items_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $optionItem = new OptionItems();
        $form = $this->createForm(OptionItemsType::class, $optionItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($optionItem);
            $entityManager->flush();

            return $this->redirectToRoute('option_items_index');
        }

        return $this->render('option_items/new.html.twig', [
            'option_item' => $optionItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_items_show", methods={"GET"})
     */
    public function show(OptionItems $optionItem): Response
    {
        return $this->render('option_items/show.html.twig', [
            'option_item' => $optionItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="option_items_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OptionItems $optionItem): Response
    {
        $form = $this->createForm(OptionItemsType::class, $optionItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('option_items_index');
        }

        return $this->render('option_items/edit.html.twig', [
            'option_item' => $optionItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_items_delete", methods={"POST"})
     */
    public function delete(Request $request, OptionItems $optionItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optionItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optionItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('option_items_index');
    }
}
