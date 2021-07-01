<?php

namespace App\Controller;

use App\Entity\Tables;
use App\Entity\Transactions;
use App\Form\TablesType;
use App\Repository\ProductOrdreRepository;
use App\Repository\TablesRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;



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


                //dump($productOrdre);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($productOrdre);
                $entityManager->flush();

                
            }
        }


        // print ticket
        

        if ($method == 'POST') {

            if ($parameters->get('printer') != null) {
                try {
                    $connector = new NetworkPrintConnector("10.x.x.x", 9100);
                
                $items = array(
                    
                );
                $total = 0;

                foreach ($table->getTableOrdres() as $key => $ordre) {
                    

                    if ($ordre->getStatus() == 0) {
                       
                        foreach ($ordre->getProductOrdres() as $key => $product) {

                            $title = ( $product->getProduct()->getLabel() );
                            $quantity =  ( $product->getQuantity() );
                            $price = ( $product->getunitPrice() );
                            
                            $item = new item($title.' x '.$quantity,  ''.$quantity*$price.'', true );
                            $total+=$quantity*$price;
                            array_push($items, $item);
                            
                        }




                    }
                }


                /* Information for the receipt */
                
                //$subtotal = new item('Total', '12.95');
                //$tax = new item('A local tax', '1.30');
                $total = new item('Total', ''.$total.'', true);
                // Date is kept the same for testing */
                $date = date('Y/d/m H:i');

                /* Start the printer */
                //$logo = EscposImage::load("resources/escpos-php.png", false);
                $printer = new Printer($connector);

                /* Print top logo */
               // $printer -> setJustification(Printer::JUSTIFY_CENTER);
               // $printer -> graphics($logo);

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text("Paradise Grill.\n");
                $printer -> feed();

                /* Title of receipt */
                $printer -> setEmphasis(true);
                $printer -> text("Ticket\n");
                $printer -> setEmphasis(false);

                /* Items */
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(false);
                foreach ($items as $item) {
                    $printer -> text($item);
                }
                /*$printer -> setEmphasis(true);
                $printer -> text($subtotal);
                $printer -> setEmphasis(false);
                $printer -> feed();*/

                /*Tax and total */
                //$printer -> text($tax);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text($total);
                $printer -> selectPrintMode();

                /* Footer */
                $printer -> feed(2);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Merci pour votre visite\n");
                $printer -> text("Pour plus d'informations, rendez-vous sur notre site internet paradise-grill.fr\n");
                $printer -> feed(2);
                $printer -> text($date . "\n");
                } catch (\Throwable $th) {
                    
                }


               

                
            }
        }



        // close table
        
        if ($method == 'POST') {

            if ($parameters->get('closeTable') != null) {
                $orders = $table->getTableOrdres();
                
                $items = array(
                    
                );
                $total = 0;

                foreach ($orders as $key => $ordre) {
                    

                    if ($ordre->getStatus() == 0) {
                        $ordre->setStatus(1);

                        $this->getDoctrine()->getManager()->flush();
                       
                        foreach ($ordre->getProductOrdres() as $key => $product) {

                            $title = ( $product->getProduct()->getLabel() );
                            $quantity =  ( $product->getQuantity() );
                            $price = ( $product->getunitPrice() );
                            
                            $item = new item($title.' x '.$quantity,  ''.$quantity*$price.'', true );
                            $total+=$quantity*$price;
                            array_push($items, $item);
                            
                        }




                    }
                }


                $transaction = new Transactions();
                $transaction->setTitle("Clôture table".$table->getNumber());
                $transaction->setDescription("Clôture table N°".$table->getNumber());
                $transaction->settype(1);
                $transaction->setAmount($total);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($transaction);
                $entityManager->flush();





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


class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;
        
        $sign = ($this -> dollarSign ? '€ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}