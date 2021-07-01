<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\ClientsReviewsRepository;
use App\Repository\ProductsRepository;
use App\Repository\TablesRepository;
use App\Repository\TransactionsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="app_welcome")
     */
    public function welcome(): Response
    {
       

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/home", name="app_home")
     */
    public function login(ClientsReviewsRepository $clientsReviewsRepository, ProductsRepository $productsRepository, TablesRepository $tablesRepository, TransactionsRepository $transactionsRepository ): Response
    {
       
        /* count products  */
        $outOfStockProducts = $productsRepository->findBy(array('quantity'=>0));
        $products = $productsRepository->findAll();

        $soonOutOfStock = array();

        foreach ($products as $key => $value) {
            if ($value->getQuantity() <= 5) {
                array_push($soonOutOfStock,$value);
            }
        }
        

        /** open tables */
        $table = $tablesRepository->findAll();
        $openTables = array();
        foreach ($table as $key => $value) {
            if ($value->getStatus() != 0) {
                array_push($openTables,$value);
            }
        }


        /**annual revenue */
        $transcations = $transactionsRepository->findBy(array(),array('id'=>'DESC'));

        

        $cashFlow = 0;

        foreach ($transcations as $key => $value) {
            $today = new DateTime();

            if ( $value->getOccureDate()->format('Y') == $today->format('Y') ) {
                if ($value->gettype() == 0) {
                    $cashFlow -= $value->getAmount();
                }else{
                    $cashFlow += $value->getAmount();
                }
            }
        }

        // this week chart
        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM transactions t ORDER BY t.`occure_date` ASC LIMIT 7';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $chartTransaction = $stmt->fetchAllAssociative();

        dump($chartTransaction);

        $clientsReviews= $clientsReviewsRepository->findBy(array('isSeen'=>false));


        
        return $this->render('app/index.html.twig',[
            "outOfStockProducts"=>$outOfStockProducts,
            "soonOutOfStock"=>$soonOutOfStock,
            "openTables"=>$openTables,
            "cashFlow"=>$cashFlow,
            "chartTransaction"=>$chartTransaction,
            "clientsReviews"=>$clientsReviews
        ]);
    }





    

    


    
}
