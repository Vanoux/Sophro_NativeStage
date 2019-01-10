<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="user_registration")
     */
    
    // Formulaire d'inscription
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) //Userpasswordencoderinterface = permet d'encoder les mdp
    {
        //Nouvel utilisateur
        $user = new User();
        $user->setRoles(array('ROLE_USER'));

        //Création du formulaire 
        $form = $this->createForm(RegistrationType::class, $user);

        //Permet au formulaire d'analyser la requête = traitement du formulaire
        $form->handleRequest($request);

        //Si le formulaire est soumit et que tout les champs sont valide
        if($form->isSubmitted() && $form->isValid()){
            
            //Avant de sauvegarder l'utilisateur = hash du mdp
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            //Puis fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($user);
            //Et le sauvegarde dans la bdd
            $manager->flush();

            //Une fois enregistré, l'utilisateur est redirigé à la page de connexion
            return $this->redirectToRoute('security_login');
        }


        //Rendu de la page inscription avec le formulaire
        return $this->render('security/registration.html.twig', [
           'form' => $form->createView() 
        ]);
    }


    /**
     * @Route("/login", name="security_login")
     */
    //Connexion
    public function login()
    {
       return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    //Deconnexion
    public function logout()
    {

    }



}
