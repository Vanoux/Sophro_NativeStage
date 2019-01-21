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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

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
            //Télécharge l'image
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('images_directory'), $imageName); 
            $articles->setImage($imageName);
            //Puis enregistre et envoi dans la BDD
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
        /*
         *
         * Ici nous mettons la conversion du string contenu dans articles->getImage(). De ce fait le formulaire sait qu'on utilise
         * Un fichier File
         *
         */
        $articles->setImage(
            new File($this->getParameter('images_directory').'/'.$articles->getImage())
        );

        $form = $this->createForm(ArticlesType::class, $articles);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            /*
             *
             * Ici tu vas devoir gérer toute la logique d'image que je t'ai indiqué dans la journée.
             * Tu as trois cas de figure :
             *
             * 1) Tu edites le contenu mais l'image reste la même
             * 2) Tu édites l'image, dans ce cas on doit supprimer l'ancienne et en mettre une nouvelle
             * 3) Tu n'avais pas d'image au départ et tu veux en mettre une désormais
             *
             * À toi de me faire une belle logique conditionnel pour gérer ça :)
             *
             */
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
     * @Route("/{id}", name="actu_delete", methods={"DELETE"})
     */
    public function delete_actu(Request $request, ObjectManager $manager, Articles $articles): Response 
    {
        if ($this->isCsrfTokenValid('delete'.$articles->getId(), $request->request->get('_token'))) {
            $manager->remove($articles);
            $manager->flush();
        }
        $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été supprimé !');
        return $this->redirectToRoute('myActu');
    }

}