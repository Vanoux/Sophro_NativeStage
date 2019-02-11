<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/actu")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="myActu", methods={"GET"})
     */
    public function actu(ArticleRepository $articleRepository): Response 
    {
        $articles = $articleRepository->findAll();
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
        $article = new Article();
        //Définit par défaut la date du jour pour la création de l'article
        $article->setDate(new \DateTime('now'));
        //Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request); //Traitement du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            //Dis qu'un article est lié à un utilisateur
            $article->setUser($user);
            //Télécharge l'image, la convertie et la stocke dans le parameter 
            $image = $form->get('image')->getData(); 
            if($image !== null){
                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName); 
                $article->setImage($imageName);
            }
            //Puis enregistre et envoi dans la BDD
            $manager->persist($article);
            $manager->flush();
            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été créé !');
            
            //Fait une redirection
            return $this->redirectToRoute('myActu', [
                'id' => $article->getId()
                ]);
        }
        return $this->render('admin/actu/new.html.twig', [
            'article' => $article,
            'formArticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actu_edit", methods={"GET","POST"})
     */
    public function edit_actu(Request $request, ObjectManager $manager, Article $article): Response
    {
        //Je récupère et stocke dans une variable l'image existante de l'article
        $existingImage = $article->getImage();

        //Je crée le formulaire d'édition
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        //Si le Formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //Je récupère et stocke dans une variable l'image envoyé du formulaire 
            $image = $form->get('image')->getData();

            //Si il y a une image envoyé par le formulaire alors je m'occupe de son upload & convertion
            if($image !== null) {
                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName); 
                $article->setImage($imageName);
                //Si il y avait une image avant alors je la supprime de l'article pour la remplacer par la nouvelle 
                if($existingImage !== null) {
                    unlink($this->getParameter('images_directory').'/'.$existingImage);
                }
            }//Sinon je ne change pas d'image, alors je laisse celle actuelle dans l'article
            else {
                $article->setImage($existingImage);
            }
            $manager->persist($article);
            $manager->flush();
            $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été modifié !');
            return $this->redirectToRoute('myActu', [
                'id' => $article->getId()
            ]);
        }
        return $this->render('admin/actu/edit.html.twig', [
            'article' => $article,
            'formArticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="actu_delete", methods={"DELETE"})
     */
    public function delete_actu(Request $request, ObjectManager $manager, Article $article): Response 
    {
        //Je récupère et stocke dans une variable l'image existante de l'article
        $existingImage = $article->getImage();

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            //Si il y avait une image dans l'article alors je la supprime du dossier où elle est stockée
            if($existingImage !== null) {
                unlink($this->getParameter('images_directory').'/'.$existingImage);
            }
            $manager->remove($article);
            $manager->flush();
        }
        $this->get('session')->getFlashBag()->add('success', 'Votre article a bien été supprimé !');
        return $this->redirectToRoute('myActu');
    }

}
