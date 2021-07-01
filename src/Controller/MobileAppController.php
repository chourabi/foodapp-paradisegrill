<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\ProductOrdre;
use App\Entity\ProductOrdreItems;
use App\Entity\TableOrdre;
use App\Repository\AdminRepository;
use App\Repository\CategorieRepository;
use App\Repository\OptionItemsRepository;
use App\Repository\OptionsRepository;
use App\Repository\ProductsRepository;
use App\Repository\TableOrdreRepository;
use App\Repository\TablesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/mobile")
 */
class MobileAppController extends AbstractController
{
    private SerializerInterface $serializer;


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
    * @Route("/produit/{id}", name="api_produits_list")
    */
    public function ProduitDetails($id): JsonResponse
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
        

        FROM `products`,sub_categories,product_types,categorie WHERE
        products.id = ? AND
        products.category_id = sub_categories.id and sub_categories.product_type_id = product_types.id AND sub_categories.categorie_id = categorie.id AND products.is_active = 1 ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));

        // returns an array of arrays (i.e. a raw data set)
        $products =  $stmt->fetchAllAssociative();

        $product = null;
        if (sizeof($products) == 1) {
            $product = $products[0];
        }

        // get available options
        $sql ='
        SELECT 

        option_items.nom AS item,
        items_to_options.linked_item_id as id,
        options.nom as option_name

        FROM `items_to_options`,option_items,options WHERE items_to_options.linked_option_id = options.id AND items_to_options.linked_item_id = option_items.id AND items_to_options.linked_option_id = ?
        ';

        // get first the options
        $optionsListSql = 'SELECT * FROM `option_to_products`,options WHERE option_to_products.product_option_id = options.id AND option_to_products.product_id = ?';
        $stmt = $conn->prepare($optionsListSql);
        $stmt->execute(array($id));

        // returns an array of arrays (i.e. a raw data set)
        $optionsList =  $stmt->fetchAllAssociative();

        $finalOptions=array();

        for ($i=0; $i < sizeof($optionsList); $i++) { 
            // get the items and push in array

            // get first the options
            $itemsSQL = 'SELECT * FROM `items_to_options`,option_items WHERE items_to_options.linked_item_id = option_items.id AND items_to_options.linked_option_id = ?';
            $itemStmt = $conn->prepare($itemsSQL);
            $itemStmt->execute(array($optionsList[$i]['id']));

            // returns an array of arrays (i.e. a raw data set)
            $items =  $itemStmt->fetchAllAssociative();

            $optionDetails =array(
                "id"=> $optionsList[$i]['id'],
                "nom"=> $optionsList[$i]['nom'],
                "nombre_maximum_option"=> $optionsList[$i]['nombre_maximum_option'],
                "prix_option_supp"=> $optionsList[$i]['prix_option_supp']
            );


            array_push($finalOptions,array('option'=>$optionDetails,'items'=>$items));

        }

        $final = array('details'=>$product,'options'=>$finalOptions);

        return $this->json($final);
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

        $sql = 'SELECT categorie.id,categorie.categorie,categorie.icon_class_name  FROM `categorie`,sub_categories WHERE categorie.id = sub_categories.categorie_id AND categorie.is_active = 1 GROUP BY categorie.id';
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

    /**
     * @Route("/sendOrder", name="api_add_new_ordre_list"  )
     */
    public function addNewOrdre(Request $request,
    TablesRepository $tablesRepository,
    ProductsRepository $productsRepository,
    OptionItemsRepository $optionItemsRepository,
    OptionsRepository $optionsRepository,
    TableOrdreRepository $tableOrdreRepository
    ): Response
    {
      
        $method = $request->getMethod();

        if ($method == 'POST') {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
    
            $tableID = $request->request->get('tableId');
            $orders =  is_array($request->request->get('orders')) ? $request->request->get('orders') : array() ;
            $table = $tablesRepository->findOneBy(array('uniqueID'=>$tableID));
            
            
            $tableOrdre = $tableOrdreRepository->findOneBy(array('status'=>0,'tableRef'=>$table));


            if ($tableOrdre == null) {
                
                $tableOrdre = new TableOrdre();
                $tableOrdre->setTableRef($table);
                $tableOrdre->setStatus(0);
                $tableOrdre->setAddDate(new DateTime());
            }
            
            
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tableOrdre);
            $entityManager->flush();
    
            // the staus will be 1 once the table is scanned
    
            // now we update table status
            $table->setStatus(2);
            $this->getDoctrine()->getManager()->flush();

            // now we hundle the associated products

            foreach ($orders as $key => $value) {
                $productOrdre = new ProductOrdre();
                $productOrdre->setQuantity($orders[$key]['quantity']);
                $productOrdre->setOtherInfo($orders[$key]['additionalIformation']);
                $productOrdre->setOrdre($tableOrdre);
                $productOrdre->setUnitPrice($orders[$key]['unitPrice']);
                

                // get the product
                $productId = $orders[$key]['product']['id'];
                $product = $productsRepository->findOneBy(array('id'=>$productId));

                // update product quantity
                $product->setQuantity(  (($product->getQuantity()) - $orders[$key]['quantity'] )  );
                $this->getDoctrine()->getManager()->flush();

                $productOrdre->setProduct($product);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($productOrdre);
                $entityManager->flush();
                
                // add items now

                foreach ($orders[$key]['items'] as $key => $itemLoop) {
                    $productOrdreItem = new ProductOrdreItems();

                    $items = $optionItemsRepository->findOneBy(array('id'=>$itemLoop['linked_item_id']));
                    $option = $optionsRepository->findOneBy(array('id'=>$itemLoop['linked_option_id']));
                    

                    $productOrdreItem->setItem($items);
                    $productOrdreItem->setOptionRef($option);
                    $productOrdreItem->setProductOrdre($productOrdre);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($productOrdreItem);
                    $entityManager->flush();
                    
                }
                
            }
            
            return $this->json(array("ordre_id"=>$productOrdre->getId()));
        }


        return $this->json(["message"=>"access denied"]);
        
    }



    /**
     * @Route("/table_status/{uniqueID}", name="api_get_table_status"  )
     */
    public function tableStatus($uniqueID,Request $request,
    TablesRepository $tablesRepository,
    ProductsRepository $productsRepository,
    OptionItemsRepository $optionItemsRepository,
    OptionsRepository $optionsRepository,
    TableOrdreRepository $tableOrdreRepository
    ): Response
    {
      


            $table = $tablesRepository->findOneBy(array('uniqueID'=>$uniqueID));

            
            
            $orders= $table->getTableOrdres();
            $products = array();

            foreach ($orders as $key => $order) {
                if ($order->getStatus() == 0) {
                    // get the products
                    $productOrdres = $order->getProductOrdres();

                    foreach ($productOrdres as $key => $productOrdre) {
                        array_push($products, array('quantity'=>$productOrdre->getQuantity(),'unitPrice'=>$productOrdre->getUnitPrice()));
                    }

                    
                }
            }
            
                
            
            
           
        


        return $this->json(["status"=>$table->getStatus(), 'products'=>$products]);
        
    }



    /**
     * @Route("/tables_list", name="api_get_tables_list"  )
     */

    public function tablesList(Request $request,
    TablesRepository $tablesRepository
    ): Response
    {
      


            $tablesList = $tablesRepository->findAll();
            
            $tables = array();

            foreach ($tablesList as $key => $t) {
                array_push($tables, array( "number"=>$t->getNumber(), "status"=>$t->getStatus(),"uniqueID"=>$t->getUniqueID() )  );
            }
           
        


            return $this->json($tables);
        
    }
    


    /**
     * @Route("/table_open/{uniqueID}", name="api_table_open"  )
     */
    public function openTable($uniqueID,Request $request,
    TablesRepository $tablesRepository
    ): Response
    {
      


        try {
            $table = $tablesRepository->findOneBy(array('uniqueID'=>$uniqueID));

            
            
            if ($table->getStatus() == 0 ) {
                $table->setStatus(1);
                $this->getDoctrine()->getManager()->flush();
            }
            


            return $this->json(["status"=>$table->getStatus(), "success"=>true ] );
        } catch (\Throwable $th) {
            return $this->json(["success"=>false ] );
        }
        
    }



    /**
     * @Route("/table_check/{uniqueID}", name="api_table_check"  )
     */
    public function checkTableExistance($uniqueID,Request $request,
    TablesRepository $tablesRepository
    ): Response
    {
      


        try {
            $table = $tablesRepository->findOneBy(array('uniqueID'=>$uniqueID));

            return $this->json(["success"=>true ] );
        } catch (\Throwable $th) {
            return $this->json(["success"=>false ] );
        }
        
    }



    
    

    


    
}
