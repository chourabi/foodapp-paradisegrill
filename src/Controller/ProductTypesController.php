<?php

namespace App\Controller;

use App\Entity\ProductTypes;
use App\Form\ProductTypesType;
use App\Repository\ProductTypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product/types")
 */
class ProductTypesController extends AbstractController
{
    /**
     * @Route("/", name="product_types_index", methods={"GET"})
     */
    public function index(ProductTypesRepository $productTypesRepository): Response
    {
        return $this->render('product_types/index.html.twig', [
            'product_types' => $productTypesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_types_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productType = new ProductTypes();
        $form = $this->createForm(ProductTypesType::class, $productType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productType);
            $entityManager->flush();

            return $this->redirectToRoute('product_types_index');
        }

        return $this->render('product_types/new.html.twig', [
            'product_type' => $productType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_types_show", methods={"GET"})
     */
    public function show(ProductTypes $productType): Response
    {
        return $this->render('product_types/show.html.twig', [
            'product_type' => $productType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_types_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductTypes $productType): Response
    {
        $form = $this->createForm(ProductTypesType::class, $productType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_types_index');
        }

        return $this->render('product_types/edit.html.twig', [
            'product_type' => $productType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_types_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductTypes $productType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_types_index');
    }
}
