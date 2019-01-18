<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\EditPasswordType;

class UserController extends AbstractController
{

    /**
     * @Route("/inscription", name="user_registration")
     */
    // Formulaire d'inscription
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) { //Userpasswordencoderinterface = permet d'encoder les mdp 
        //Nouvel utilisateur
        $user = new User();
        //Par défaut l'utilisateur à le role user
        $user->setRoles(array('ROLE_USER'));
        //Création du formulaire
        $form = $this->createForm(RegistrationType::class, $user);
        //Permet au formulaire d'analyser la requête = traitement du formulaire
        $form->handleRequest($request);
        //Si le formulaire est soumit et que tout les champs sont valide
        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/user/edit", name="userEdit")
     */
    public function edit(Request $request, ObjectManager $manager) {
        $user = $this->getUser();
        //Création du formulaire 
        $form = $this->createForm(EditUserType::class, $user);
        //Permet au formulaire d'analyser la requête = traitement du formulaire
        $form->handleRequest($request);
        //Si le formulaire est soumit et que tout les champs sont valide
        if ($form->isSubmitted() && $form->isValid()) {
            //Fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($user);
            //Et le sauvegarde dans la bdd
            $manager->flush();
            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Vos informations ont bien été modifiées !');
            return $this->redirectToRoute('user');
        }
        return $this->render('user/userEditForm.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

}
