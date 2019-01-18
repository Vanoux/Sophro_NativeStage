<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;


class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response 
    {
        return $this->render('app/home.html.twig');
    }

    /**
     * @Route("/sophrologie", name="sophrologie")
     */
    public function sophro(): Response  
    {
        return $this->render('app/sophro.html.twig');
    }

    /**
     * @Route("/ateliers", name="ateliers")
     */
    public function ateliers(): Response  
    {
        return $this->render('app/ateliers.html.twig');
    }

    /**
     * @Route("/tarifsHoraires", name="tarifsHoraires")
     */
    public function tarifsHoraires() : Response 
    {
        return $this->render('app/tarifs_horaires.html.twig');
    }

    /**
     * @Route("/faq", name="faq", methods={"GET"})
     */
    public function index(FaqRepository $faqRepository): Response 
    {
        return $this->render('app/faq.html.twig', [
            'faqs' => $faqRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/actualites", name="actu")
     */
    public function actu(ArticlesRepository $articlesRepository): Response  
    {
        $articles = $articlesRepository->findAll();
        return $this->render('app/actu.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/actualites/{id}", name="actu_show")
     */
    public function showActu(Articles $articles): Response  
    {
        return $this->render('app/actu_show.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response 
    {
        return $this->render('app/contact.html.twig');
    }

    /**
     * @Route("/conseils", name="conseils")
     */
    public function conseils(): Response  
    {
        return $this->render('app/conseils.html.twig');
    }
    
}
