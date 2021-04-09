<?php

namespace App\Controller;

use App\Entity\ItemsToOptions;
use App\Entity\Options;
use App\Form\ItemsToOptionsType;
use App\Form\OptionsType;
use App\Repository\ItemsToOptionsRepository;
use App\Repository\OptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/options")
 */
class OptionsController extends AbstractController
{
    /**
     * @Route("/", name="options_index", methods={"GET"})
     */
    public function index(OptionsRepository $optionsRepository): Response
    {
        return $this->render('options/index.html.twig', [
            'options' => $optionsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="options_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $option = new Options();
        $form = $this->createForm(OptionsType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('options_index');
        }

        return $this->render('options/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="options_show", methods={"GET"})
     */
    public function show(Options $option): Response
    {

        


        return $this->render('options/show.html.twig', [
            'option' => $option,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="options_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Options $option,$id, ItemsToOptionsRepository $itemsToOptionsRepository): Response
    {
        $form = $this->createForm(OptionsType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('options_index');
        }



        $ItemToOption = new ItemsToOptions();
        $ItemToOption->setLinkedOption($option);
        $ItemToOptionForm = $this->createForm(ItemsToOptionsType::class, $ItemToOption);
        $ItemToOptionForm->handleRequest($request);


        if ($ItemToOptionForm->isSubmitted() && $ItemToOptionForm->isValid()) {
            $ItemToOption->setLinkedOption($option);

            // check before add
            $res= $itemsToOptionsRepository->findBy(array('linkedOption'=>$option,'linkedItem'=>$ItemToOption->getLinkedItem()));
            if (sizeof($res) == 0) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ItemToOption);
                $entityManager->flush();
            }

            

        }

        

        // get option items
        $items = $itemsToOptionsRepository->findBy(array('linkedOption'=>$option));

        return $this->render('options/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
            'ItemToOptionForm' => $ItemToOptionForm->createView(),
            'items'=>$items
        ]);



    }

    /**
     * @Route("/{id}", name="options_delete", methods={"POST"})
     */
    public function delete(Request $request, Options $option): Response
    {
        if ($this->isCsrfTokenValid('delete'.$option->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($option);
            $entityManager->flush();
        }

        return $this->redirectToRoute('options_index');
    }
}
