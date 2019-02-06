<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\FaqRepository;
use App\Repository\ConseilRepository;
use App\Entity\Conseil;
//use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;


class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ConseilRepository $conseilRepository): Response 
    {
        $conseils = $conseilRepository->findAll();
        return $this->render('app/home.html.twig', [
            'conseils' => $conseils,
        ]);
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
    public function actu(ArticleRepository $articleRepository): Response  
    {
        // $articles = $articleRepository->findAll();
        $articles = $articleRepository->findBy(['category' => 1]);
        return $this->render('app/actu.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/actualites/{id}", name="actu_show")
     */
    public function showActu(Article $article): Response  
    {
        return $this->render('app/actu_show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/articles", name="article")
     */
    public function article(ArticleRepository $articleRepository): Response  
    {
        $articles = $articleRepository->findBy(['category' => 2]);
        return $this->render('app/article.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/articles/{id}", name="article_show")
     */
    public function showArticle(Article $article): Response  
    {
        return $this->render('app/article_show.html.twig', [
            'article' => $article
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
     * @Route("/conseil/{id}", name="conseil_show")
     */
    public function conseils(Conseil $conseil): Response  
    {
        return $this->render('app/conseil_show.html.twig', [
            'conseil' => $conseil
        ]);
    }

    /**
     * @Route("/formation", name="formation")
     */
    public function formation(): Response
    {
        return $this->render('app/formation.html.twig');
    }

    /**
     * @Route("/experiences", name="experiences")
     */
    public function experience(): Response 
    {
        return $this->render('app/experiences.html.twig');
    }
    
     /**
     * @Route("/cabinets", name="cabinets")
     */
    public function cabinets(): Response 
    {
        return $this->render('app/cabinets.html.twig');
    }
}
