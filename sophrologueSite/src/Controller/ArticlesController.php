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

/**
 * @Route("admin/actu")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="myActu", methods={"GET"})
     */
    public function actu(ArticlesRepository $articlesRepository): Response 
    {
        $articles = $articlesRepository->findAll();
        return $this->render('admin/actu/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/new", name="actu_new", methods={"GET","POST"})
     */
    public function create_actu(Request $request, ObjectManager $manager): Response 
    {
        
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
            return $this->redirectToRoute('myActu', [
                'id' => $articles->getId()
                ]);
        }
        return $this->render('admin/actu/new.html.twig', [
            'articles' => $articles,
            'formArticles' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actu_edit", methods={"GET","POST"})
     */
    public function edit_actu(Request $request, ObjectManager $manager, Articles $articles): Response 
    {

        $form = $this->createForm(ArticlesType::class, $articles);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($articles);
            $manager->flush();
            $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été modifié !');
            return $this->redirectToRoute('myActu', [
                'id' => $articles->getId()
            ]);
        }
        return $this->render('admin/actu/edit.html.twig', [
            'articles' => $articles,
            'formArticles' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="actu_delete")
     */
    public function delete_actu(Request $request, ObjectManager $manager, Articles $articles): Response 
    {
        $request->get('id');
        $manager->remove($articles);
        $manager->flush();
        $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été supprimé !');
        return $this->redirectToRoute('myActu');
    }

}