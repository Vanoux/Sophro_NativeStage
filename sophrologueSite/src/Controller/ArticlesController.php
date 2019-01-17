<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;


class ArticlesController extends AbstractController
{
    /**
     * @Route("/user/actualites", name="mesActualités")
     */
    public function actu(ArticlesRepository $articlesRepository) {
        $articles = $articlesRepository->findAll();
        return $this->render('user/mesActu.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/user/actualites/create", name="create_actu")
     */
    public function create_actu(Request $request, ObjectManager $manager) {
        
        $user = $this->getUser();
        $articles = new Articles();
        //Création du formulaire
        $form = $this->createForm(ArticlesType::class, $articles);
        $form->handleRequest($request); //Traitement du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            //Dis qu'un article est lié à un utilisateur
            $articles->setUser($user);
            //Puis l'enregistre et l'envoi dans la BDD
            $manager->persist($articles);
            $manager->flush();
            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été créé !');
            //Fait une redirection
            return $this->redirectToRoute('mesActualités', [
                'id' => $articles->getId()
                ]);
        }
        return $this->render('user/mesActu_create.html.twig', [
            'articles' => $articles,
            'formArticles' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/actualites/{id}/edit", name="edit_actu")
     */
    public function edit_actu(Request $request, ObjectManager $manager, Articles $articles) {

        $form = $this->createForm(ArticlesType::class, $articles);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($articles);
            $manager->flush();
            $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été modifié !');
            return $this->redirectToRoute('mesActualités', [
                'id' => $articles->getId()
            ]);
        }
        return $this->render('user/mesActu_edit.html.twig', [
            'articles' => $articles,
            'formArticles' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/actualites/{id}", name="delete_actu")
     */
    public function delete_actu(Request $request, ObjectManager $manager, Articles $articles) {

        $request->request->get('id');
        $manager->remove($articles);
        $manager->flush();
        $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été supprimé !');
        return $this->redirectToRoute('mesActualités');
    }


    /**
     * @Route("/user/faq", name="mesFaq")
     */
    public function faq() {
        return $this->render('user/mesFaq.html.twig');
    }

    /**
     * @Route("/user/statistiques", name="mesStat")
     */
    public function stat() {
        return $this->render('user/mesStat.html.twig');
    }

}