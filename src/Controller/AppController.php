<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
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
    public function login(): Response
    {
       

        return $this->render('app/index.html.twig');
    }





    

    


    
}
