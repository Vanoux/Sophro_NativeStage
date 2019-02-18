<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Conseil;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\FaqRepository;
use App\Repository\ArticleRepository;
use App\Repository\ConseilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; //permet d'accéder à plusieurs services de paginator avec la méthode get de la classe.

class AppController extends Controller
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
     * @Route("/tarifs-horaires", name="tarifs-horaires")
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
    public function actu(ArticleRepository $articleRepository, Request $request): Response  
    {
        //Récupération des articles par catégorie actu et tri par ordre descendant
        $articles = $articleRepository->findBy(
            ['category' => 1],
            ['id' => 'DESC']
        );
        //Instanciation du paginateur et conservation dans une variable,
        $paginator = $this->get('knp_paginator');
        //Appel la méthode paginate() en passant l'objet à paginer, la page que nous voulons, le nombre de résultats par page
        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), 2 // limite par page
        );
        return $this->render('app/actu.html.twig', [
            //'articles' => $articles,
            'pagination' => $pagination
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
    public function article(ArticleRepository $articleRepository, Request $request): Response  
    {
        //Récupération des articles par catégorie article et tri par ordre descendant
        $articles = $articleRepository->findBy(
            ['category' => 2],
            ['id' => 'DESC']
        );
        //Instanciation du paginateur et conservation dans une variable,
        $paginator = $this->get('knp_paginator');
        //Appel la méthode paginate() en passant l'objet à paginer, la page que nous voulons, le nombre de résultats par page
        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), 2 // limite par page
        );
        return $this->render('app/article.html.twig', [
            //'articles' => $articles
            'pagination' => $pagination
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
    public function contact( Request $request, \Swift_Mailer $mailer): Response 
    {
        //Nouveau contact
        $contact = new Contact();
        //Création du formulaire
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request); //Traitement du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            //Notifie ce contact là
            //$notification->notify($contact);

            //Génération d'un nouveau message avec l'instance de Swift_Mailer
            $message = (new \Swift_Message('Nouveau message de votre formulaire de contact'))
            ->setFrom($contact->getEmail())
            ->setTo('vanessa.roux891@orange.fr')
            //->setReplyTo($contact->getEmail())
            ->setBody(
                $this->renderView(
                    'app/mailContact.html.twig', 
                    ['contact' => $contact]
                ),
                'text/html'
                );
            //Envoi le mail
            $mailer->send($message);
            //Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre message à bien été envoyé ! Je vous répondrai dans les plus bref delais.');
            //Et fait une redirection vers l'accueil
            return $this->redirectToRoute('home');
        }
        return $this->render('app/contact.html.twig', [
            'formContact' => $form->createView()
        ]);
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

    /**
     * @Route("/mentions_legales", name="mentions")
     */
    public function mentions(): Response 
    {
        return $this->render('app/mentions.html.twig');
    }

     /**
     * @Route("/politique_confidentialite", name="confidential")
     */
    public function confidential(): Response 
    {
        return $this->render('app/confidential.html.twig');
    }

    /**
     * @Route("/plan_du_site", name="siteMap")
     */
    public function siteMap(ConseilRepository $conseilRepository, ArticleRepository $articleRepository, FaqRepository $faqRepository): Response 
    {
        $conseils = $conseilRepository->findAll();
        $actualites = $articleRepository->findBy(['category' => 1]);
        $articles = $articleRepository->findBy(['category' => 2]);

        return $this->render('app/siteMap.html.twig', [
            'conseils' => $conseils,
            'actualites' => $actualites,
            'articles' => $articles,
        ]);
    }
}
