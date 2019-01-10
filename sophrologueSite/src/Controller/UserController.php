<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationType;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('user/faq.html.twig');
    }

    /**
     * @Route("/actualités", name="actualités")
     */
    public function actu()
    {
        return $this->render('user/actu.html.twig');
    }
    
}
