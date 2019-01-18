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
    public function home() {
        return $this->render('app/home.html.twig');
    }

    /**
     * @Route("/sophrologie", name="sophrologie")
     */
    public function sophro() {
        return $this->render('app/infoSophro.html.twig');
    }

    /**
     * @Route("/ateliers", name="ateliers")
     */
    public function ateliers() {
        return $this->render('app/infoAteliers.html.twig');
    }

    /**
     * @Route("/tarifsHoraires", name="tarifsHoraires")
     */
    public function tarifsHoraires() {
        return $this->render('app/infoTarifsHoraires.html.twig');
    }

    /**
     * @Route("/faq", name="faq", methods={"GET"})
     */
    public function index(FaqRepository $faqRepository): Response 
    {
        return $this->render('faq/faq.html.twig', [
            'faqs' => $faqRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/actualites", name="actu")
     */
    public function actu(ArticlesRepository $articlesRepository) {
        $articles = $articlesRepository->findAll();
        return $this->render('app/actu.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/actualites/{id}", name="actu_show")
     */
    public function showActu(Articles $articles) {
        return $this->render('app/actu_show.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact() {
        return $this->render('app/contact.html.twig');
    }

    /**
     * @Route("/conseils", name="conseils")
     */
    public function conseils() {
        return $this->render('app/conseils.html.twig');
    }
    
}
