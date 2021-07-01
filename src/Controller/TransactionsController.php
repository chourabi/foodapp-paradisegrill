<?php

namespace App\Controller;

use App\Entity\Transactions;
use App\Form\TransactionsType;
use App\Repository\TransactionsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/transactions")
 */
class TransactionsController extends AbstractController
{
    /**
     * @Route("/", name="transactions_index", methods={"GET"})
     */
    public function index(TransactionsRepository $transactionsRepository): Response
    {
                /**annual revenue */
                $transcations = $transactionsRepository->findBy(array(),array('id'=>'DESC'));

        

                $annualCashFlow = 0;
        
                foreach ($transcations as $key => $value) {
                    $today = new DateTime();
        
                    if ( $value->getOccureDate()->format('Y') == $today->format('Y') ) {
                        if ($value->gettype() == 0) {
                            $annualCashFlow -= $value->getAmount();
                        }else{
                            $annualCashFlow += $value->getAmount();
                        }
                    }


                }

                $daylyCashFlow = 0;
        
                foreach ($transcations as $key => $value) {
                    $today = new DateTime();
        
                    if ( ($value->getOccureDate()->format('Y') == $today->format('Y')) and 
                    ( $value->getOccureDate()->format('d') == $today->format('d') ) and
                    ( $value->getOccureDate()->format('m') == $today->format('m') )
                    ) {
                        if ($value->gettype() == 0) {
                            $daylyCashFlow -= $value->getAmount();
                        }else{
                            $daylyCashFlow += $value->getAmount();
                        }
                    }


                }

                $monthlyCashFlow = 0;
        
                foreach ($transcations as $key => $value) {
                    $today = new DateTime();
        
                    if ( ($value->getOccureDate()->format('Y') == $today->format('Y')) and 
                    ( $value->getOccureDate()->format('m') == $today->format('m') )
                    ) {
                        if ($value->gettype() == 0) {
                            $monthlyCashFlow -= $value->getAmount();
                        }else{
                            $monthlyCashFlow += $value->getAmount();
                        }
                    }


                }

                

                	

                


        return $this->render('transactions/index.html.twig', [
            'transactions' => $transactionsRepository->findAll(),
            'annualCashFlow'=>$annualCashFlow,
            "daylyCashFlow"=>$daylyCashFlow,
            "monthlyCashFlow"=>$monthlyCashFlow
        ]);
    }

    /**
     * @Route("/new", name="transactions_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $transaction = new Transactions();
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('transactions_index');
        }

        return $this->render('transactions/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transactions_show", methods={"GET"})
     */
    public function show(Transactions $transaction): Response
    {
        return $this->render('transactions/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="transactions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transactions $transaction): Response
    {
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transactions_index');
        }

        return $this->render('transactions/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transactions_delete", methods={"POST"})
     */
    public function delete(Request $request, Transactions $transaction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('transactions_index');
    }
}
