<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('app/home.html.twig');
    }

    /**
     * @Route("/sophrologie", name="sophrologie")
     */
    public function sophro()
    {
        return $this->render('app/infoSophro.html.twig');
    }

    /**
     * @Route("/ateliers", name="ateliers")
     */
    public function ateliers()
    {
        return $this->render('app/infoAteliers.html.twig');
    }

    /**
     * @Route("/tarifsHoraires", name="tarifsHoraires")
     */
    public function tarifsHoraires()
    {
        return $this->render('app/infoTarifsHoraires.html.twig');
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('app/faq.html.twig');
    }

    /**
     * @Route("/actualités", name="actualités")
     */
    public function actu()
    {
        return $this->render('app/actu.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('app/contact.html.twig');
    }

    /**
     * @Route("/conseil1", name="conseil1")
     */
    public function conseil1()
    {
        return $this->render('app/conseil1.html.twig');
    }
    
}
