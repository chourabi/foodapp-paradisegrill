<?php

namespace App\Controller;

use App\Entity\SubCategories;
use App\Form\SubCategoriesType;
use App\Repository\SubCategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sub/categories")
 */
class SubCategoriesController extends AbstractController
{
    /**
     * @Route("/", name="sub_categories_index", methods={"GET"})
     */
    public function index(SubCategoriesRepository $subCategoriesRepository): Response
    {
        return $this->render('sub_categories/index.html.twig', [
            'sub_categories' => $subCategoriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sub_categories_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subCategory = new SubCategories();
        $form = $this->createForm(SubCategoriesType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subCategory);
            $entityManager->flush();

            return $this->redirectToRoute('sub_categories_index');
        }

        return $this->render('sub_categories/new.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sub_categories_show", methods={"GET"})
     */
    public function show(SubCategories $subCategory): Response
    {
        return $this->render('sub_categories/show.html.twig', [
            'sub_category' => $subCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sub_categories_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SubCategories $subCategory): Response
    {
        $form = $this->createForm(SubCategoriesType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sub_categories_index');
        }

        return $this->render('sub_categories/edit.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sub_categories_delete", methods={"POST"})
     */
    public function delete(Request $request, SubCategories $subCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sub_categories_index');
    }
}
