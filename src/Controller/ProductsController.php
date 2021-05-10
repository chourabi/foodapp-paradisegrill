<?php

namespace App\Controller;

use App\Entity\OptionToProducts;
use App\Entity\Products;
use App\Form\OptionToProductsType;
use App\Form\ProductsType;
use App\Form\ProductsTypeEdit;
use App\Repository\OptionToProductsRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/products")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="products_index", methods={"GET"})
     */
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="products_new", methods={"GET","POST"})
     */
    public function new(Request $request,SluggerInterface $slugger): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


                        /* hundle image upload */

            
                /** @var UploadedFile $image */
                $image = $form->get('photoURL')->getData();
    
                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($image) {
                    
                    $newFilename = uniqid().'.'.$image->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    try {

                        
                        $image->move('img/produits',$newFilename);

                        

                    } catch (FileException $e) {
                        
                    }
    
                    // updates the 'imagename' property to store the PDF file name
                    // instead of its contents
                    $product->setPhotoURL($newFilename);
                    $product->setIsPromoted(false);
                    $entityManager->persist($product);
                    $entityManager->flush();

                    return $this->redirectToRoute('products_index');
                }else{
                    
                }
                
                




            
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="products_show", methods={"GET"})
     */
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="products_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Products $product,OptionToProductsRepository $optionToProductsRepository): Response
    {
        $form = $this->createForm(ProductsTypeEdit::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var UploadedFile $image */
            $image = $form->get('photoURL')->getData();
    
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                
                $newFilename = uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {

                    
                    $image->move('img/produits',$newFilename);

                    

                } catch (FileException $e) {
                    
                }

                // updates the 'imagename' property to store the PDF file name
                // instead of its contents
                $product->setPhotoURL($newFilename);
                

                
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products_index');
        }

        $optionToProduct = new OptionToProducts();

        $optionProductForm = $this->createForm(OptionToProductsType::class, $optionToProduct);
        $optionProductForm->handleRequest($request);

        if ($optionProductForm->isSubmitted() && $optionProductForm->isValid()) {
            
            $res = $optionToProductsRepository->findBy(array('productOption'=>$optionToProduct->getProductOption(),'product'=>$product));
            if (sizeof($res)==0) {
                $optionToProduct->setProduct($product);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($optionToProduct);
                $entityManager->flush();
                
            }
        }


        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'option_to_products' => $optionToProductsRepository->findBy(array('product'=>$product)),
            'option_to_product' => $optionToProduct,
            'optionProductForm'=>$optionProductForm->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="products_delete", methods={"POST"})
     */
    public function delete(Request $request, Products $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products_index');
    }
}
