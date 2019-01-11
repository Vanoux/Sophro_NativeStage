<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/dashboard.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/edit", name="userEdit")
     */
    public function edit(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        //Création du formulaire 
        $form = $this->createForm(EditUserType::class, $user);

        //Permet au formulaire d'analyser la requête = traitement du formulaire
        $form->handleRequest($request);

        //Si le formulaire est soumit et que tout les champs sont valide
        if($form->isSubmitted() && $form->isValid()){


            //Puis fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($user);
            //Et le sauvegarde dans la bdd
            $manager->flush();

            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Profile updated');
            return $this->redirectToRoute('user');
        }
        return $this->render('user/userEditForm.html.twig', [
        "user" => $user,
        "form" => $form->createView()
        ]);

    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('user/faq.html.twig');
    }

    /**
     * @Route("/actualités", name="actualités")
     */
    public function actu()
    {
        return $this->render('user/actu.html.twig');
    }

    /**
     * @Route("/statistiques", name="stat")
     */
    public function stat()
    {
        return $this->render('user/stat.html.twig');
    }

    
}
