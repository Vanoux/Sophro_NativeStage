<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="admin_registration")
     */
    
    // Formulaire d'inscription
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) //Userpasswordencoderinterface = permet d'encoder les mdp
    {
        //Nouvel utilisateur
        $admin = new Admin();

        //Création du formulaire 
        $form = $this->createForm(RegistrationType::class, $admin);

        //Permet au formulaire d'analyser la requête
        $form->handleRequest($request);

        //Si le formulaire est soumit et que tout les champs sont valide
        if($form->isSubmitted() && $form->isValid()){
            
            //Avant de sauvegarder l'utilisateur = hash du mdp
            $hash = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($hash);

            //Puis fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($admin);
            //Et le sauvegarde dans la bdd
            $manager->flush();

            //Une fois enregistré, l'utilisateur est redirigé à la page de connexion
            return $this->redirectToRoute('home');
        }


        //Rendu de la page inscription avec le formulaire
        return $this->render('security/registration.html.twig', [
           'form' => $form->createView() 
        ]);
    }
}
