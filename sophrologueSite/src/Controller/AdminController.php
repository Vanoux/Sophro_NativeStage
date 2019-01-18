<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\EditPasswordType;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="dashboard")
     */
    public function index() {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    
    /**
     * @Route("/admin/stat", name="myStat")
     */
    public function stat() {
        return $this->render('admin/myStat.html.twig');
    }
}
    