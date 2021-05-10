<?php

namespace App\Controller;

use App\Entity\OptionToProducts;
use App\Form\OptionToProductsType;
use App\Repository\OptionToProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/optionproduct")
 */
class OptionToProductsController extends AbstractController
{
    /**
     * @Route("/", name="option_to_products_index", methods={"GET"})
     */
    public function index(OptionToProductsRepository $optionToProductsRepository): Response
    {
        return $this->render('option_to_products/index.html.twig', [
            'option_to_products' => $optionToProductsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="option_to_products_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $optionToProduct = new OptionToProducts();
        $form = $this->createForm(OptionToProductsType::class, $optionToProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($optionToProduct);
            $entityManager->flush();

            return $this->redirectToRoute('option_to_products_index');
        }

        return $this->render('option_to_products/new.html.twig', [
            'option_to_product' => $optionToProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_to_products_show", methods={"GET"})
     */
    public function show(OptionToProducts $optionToProduct): Response
    {
        return $this->render('option_to_products/show.html.twig', [
            'option_to_product' => $optionToProduct,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="option_to_products_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OptionToProducts $optionToProduct): Response
    {
        $form = $this->createForm(OptionToProductsType::class, $optionToProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('option_to_products_index');
        }

        return $this->render('option_to_products/edit.html.twig', [
            'option_to_product' => $optionToProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_to_products_delete", methods={"POST"})
     */
    public function delete(Request $request, OptionToProducts $optionToProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optionToProduct->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optionToProduct);
            $entityManager->flush();
        }

        return $this->redirect($request->request->get('referer'));
    }
}
