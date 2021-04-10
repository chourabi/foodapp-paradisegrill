<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/api/mobile")
 */
class MobileAppController extends AbstractController
{
    

    /**
     * @Route("/produits", name="api_produits_list")
     */
    public function listProduit(ProductsRepository $productsRepository): JsonResponse
    {
       //$products = $productsRepository->findAll();

       $conn = $this->getDoctrine()->getConnection();

        $sql = '
        SELECT 
        products.id AS id,
        products.prep_time AS prep_time,
        products.label AS label,
        products.price AS price,
        products.quantity AS quantity,
        products.photo_url as photo_url,
        products.description AS description,
        sub_categories.label as category_produit,
        product_types.product_type AS product_types,
        categorie.categorie as global_category,
        products.category_id as product_sub_category,
        sub_categories.categorie_id AS global_category_id

        FROM `products`,sub_categories,product_types,categorie WHERE products.category_id = sub_categories.id and sub_categories.product_type_id = product_types.id AND sub_categories.categorie_id = categorie.id AND products.is_active = 1;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        $products =  $stmt->fetchAllAssociative();

        return $this->json($products);
    }


    
    /**
     * @Route("/produits/category/{id}", name="api_produits_sub_category_list")
     */
    public function listProduitBySubCategory($id): JsonResponse
    {
       //$products = $productsRepository->findAll();

       $conn = $this->getDoctrine()->getConnection();

        $sql = '
        SELECT 
        products.id AS id,
        products.prep_time AS prep_time,
        products.label AS label,
        products.price AS price,
        products.quantity AS quantity,
        products.photo_url as photo_url,
        products.description AS description,
        sub_categories.label as category_produit,
        product_types.product_type AS product_types,
        categorie.categorie as global_category,
        products.category_id as product_sub_category,
        sub_categories.categorie_id AS global_category_id

        FROM `products`,sub_categories,product_types,categorie 
        WHERE 
        products.category_id = ? AND
        products.category_id = sub_categories.id and sub_categories.product_type_id = product_types.id AND sub_categories.categorie_id = categorie.id AND products.is_active = 1 ORDER BY label;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));

        // returns an array of arrays (i.e. a raw data set)
        $products =  $stmt->fetchAllAssociative();

        return $this->json($products);
    }


        /**
     * @Route("/produits/parentcategory/{id}", name="api_produits_parent_category_list")
     */
    public function listProduitByMainCategory($id): JsonResponse
    {
       //$products = $productsRepository->findAll();

       $conn = $this->getDoctrine()->getConnection();

        $sql = '
        SELECT 
        products.id AS id,
        products.prep_time AS prep_time,
        products.label AS label,
        products.price AS price,
        products.quantity AS quantity,
        products.photo_url as photo_url,
        products.description AS description,
        sub_categories.label as category_produit,
        product_types.product_type AS product_types,
        categorie.categorie as global_category,
        products.category_id as product_sub_category,
        sub_categories.categorie_id AS global_category_id

        FROM `products`,sub_categories,product_types,categorie 
        WHERE 
        products.category_id = sub_categories.id AND 
        sub_categories.product_type_id = product_types.id AND sub_categories.categorie_id = categorie.id AND
        categorie.id = ? AND
        products.is_active = 1 ORDER BY label;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));

        // returns an array of arrays (i.e. a raw data set)
        $products =  $stmt->fetchAllAssociative();

        return $this->json($products);
    }

    





    /**
     * @Route("/produits/promoted", name="api_produits_pormoted_list")
     */
    public function listPromotedProduit(): JsonResponse
    {

       $conn = $this->getDoctrine()->getConnection();

        $sql = '
        SELECT 
        products.id AS id,
        products.prep_time AS prep_time,
        products.label AS label,
        products.price AS price,
        products.quantity AS quantity,
        products.photo_url as photo_url,
        products.description AS description,
        sub_categories.label as category_produit,
        product_types.product_type AS product_types,
        categorie.categorie as global_category,
        products.category_id as product_sub_category,
        sub_categories.categorie_id AS global_category_id

        FROM `products`,sub_categories,product_types,categorie WHERE products.is_promoted = 1 AND products.category_id = sub_categories.id and sub_categories.product_type_id = product_types.id AND sub_categories.categorie_id = categorie.id AND products.is_active = 1;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        $products =  $stmt->fetchAllAssociative();

        return $this->json($products);
    }


    /**
     * @Route("/categories", name="api_main_categorys_list")
     */
    public function listCategorys(): JsonResponse
    {
        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT categorie.id,categorie.categorie FROM `categorie`,sub_categories WHERE categorie.id = sub_categories.categorie_id AND categorie.is_active = 1 GROUP BY categorie.id';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        $categories =  $stmt->fetchAllAssociative();

        return $this->json($categories);
    }


        /**
     * @Route("/categories/{id}", name="api_sub_categorys_list")
     */
    public function listSubCategorys($id): JsonResponse
    {
        $conn = $this->getDoctrine()->getConnection();

        $sql = '
        SELECT 
        sub_categories.id AS filter_id,
        sub_categories.label AS filter_label,
        sub_categories.categorie_id AS filter_parent_id,
        product_types.product_type AS product_type


        FROM `sub_categories`,product_types WHERE sub_categories.`categorie_id` = ? AND sub_categories.`is_active` = 1 AND sub_categories.product_type_id = product_types.id
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));

        // returns an array of arrays (i.e. a raw data set)
        $categories =  $stmt->fetchAllAssociative();

        return $this->json($categories);
    }


    

    


    
}
