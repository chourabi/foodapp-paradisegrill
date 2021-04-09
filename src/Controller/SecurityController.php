<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @Route("/auth-login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/auth-logout", name="app_logout")
     */
    public function logout()
    {
        
    }

    
    // @Route("/____________________________________________________", name="app_create_user")
     
    public function createUser( AdminRepository $adminRepository )
    {
       /*$admin = new Admin();
       $admin->setEmail("sasparadisegrill@gmail.com");
       $admin->setPassword($this->encoder->encodePassword($admin,"Pg92320"));
       $admin->setRoles(['ROLE_ADMIN']);

       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($admin);
        $entityManager->flush();*/
    }

    
}
