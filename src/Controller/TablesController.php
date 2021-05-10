<?php

namespace App\Controller;

use App\Entity\Tables;
use App\Form\TablesType;
use App\Repository\ProductOrdreRepository;
use App\Repository\TablesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tables")
 */
class TablesController extends AbstractController
{
    /**
     * @Route("/", name="tables_index", methods={"GET"})
     */
    public function index(TablesRepository $tablesRepository): Response
    {
        return $this->render('tables/index.html.twig', [
            'tables' => $tablesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tables_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $table = new Tables();
        $form = $this->createForm(TablesType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $table->setStatus(0);
            $table->setUniqueID(uniqid($table->getNumber().time()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($table);
            $entityManager->flush();

            return $this->redirectToRoute('tables_index');
        }

        return $this->render('tables/new.html.twig', [
            'table' => $table,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tables_show", methods={"GET","POST"})
     */
    public function show(request $request,Tables $table,ProductOrdreRepository $productOrdreRepository): Response
    {
        $parameters = $request->request;
        $method = $request->getMethod();

        if ($method == 'POST') {

            if ($parameters->get('deleteProductOrdre') != null) {
                
                $productOrdre = $productOrdreRepository->findOneBy(array('id'=>$parameters->get('deleteProductOrdre')));
                $ordersList = $productOrdre->getOrdre();


                dump($productOrdre);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($productOrdre);
                $entityManager->flush();

                if (sizeof($ordersList->getProductOrdres()) == 0) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($ordersList);
                    $entityManager->flush();
                }
            }
        }



        // close table
        
        if ($method == 'POST') {

            if ($parameters->get('closeTable') != null) {
                $orders = $table->getTableOrdres();

               

                foreach ($orders as $key => $ordre) {
                    $ordre->setStatus(1);

                    $this->getDoctrine()->getManager()->flush();
                }

                $table->setStatus(0);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('tables_index');
            }
        }
        
        return $this->render('tables/show.html.twig', [
            'table' => $table,
        ]);
    }

        /**
     * @Route("/bill/{id}", name="tables_bill_show", methods={"GET","POST"})
     */
    public function facture(Tables $table): Response
    {
        
        return $this->render('tables/bill.html.twig', [
            'table' => $table,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tables_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tables $table): Response
    {
        $form = $this->createForm(TablesType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tables_index');
        }

        return $this->render('tables/edit.html.twig', [
            'table' => $table,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tables_delete", methods={"POST"})
     */
    public function delete(Request $request, Tables $table): Response
    {
        if ($this->isCsrfTokenValid('delete'.$table->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($table);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tables_index');
    }
}
