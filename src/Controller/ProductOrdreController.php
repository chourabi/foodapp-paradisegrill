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
 * @Route("/admin/productOrdre")
 */
class ProductOrdreController extends AbstractController
{
    /**
     * @Route("/up/{id}", name="productordre_up_index", methods={"GET"})
     */
    public function up($id, ProductOrdreRepository $productOrdreRepository): Response
    {
        $productOrdre = $productOrdreRepository->findOneBy(array('id'=>$id));
        $productOrdre->setQuantity( $productOrdre->getQuantity() +1 );

        $this->getDoctrine()->getManager()->flush();
       return $this->json(array("success"=>true,"quantity"=>$productOrdre->getQuantity()));
    }

    /**
     * @Route("/down/{id}", name="productordre_down_index", methods={"GET"})
     */
    public function down($id, ProductOrdreRepository $productOrdreRepository): Response
    {
        $productOrdre = $productOrdreRepository->findOneBy(array('id'=>$id));
        if ($productOrdre->getQuantity() > 1) {
            $productOrdre->setQuantity( $productOrdre->getQuantity() - 1 );

            $this->getDoctrine()->getManager()->flush();
        }
       return $this->json(array("success"=>true,"quantity"=>$productOrdre->getQuantity()));
    }

}
