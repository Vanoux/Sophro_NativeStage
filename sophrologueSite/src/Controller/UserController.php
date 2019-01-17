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
     * @Route("/user", name="user")
     */
    public function index() {
        return $this->render('user/dashboard.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/password_edit", name="passwordEdit")
     */
    public function editPassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Avant de sauvegarder la modif = hash du mdp
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            //Puis fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($user);
            //Et le sauvegarde dans la bdd
            $manager->flush();
            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre mot de passe a été modifié !');
            return $this->redirectToRoute('user');
        }
        return $this->render('user/userEditPassword.html.twig', [
            "user" => $user,
            "form" => $form->createView()
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

    /**
     * @Route("/user/faq", name="mesFaq")
     */
    public function faq() {
        return $this->render('user/mesFaq.html.twig');
    }

    /**
     * @Route("/user/actualités", name="mesActualités")
     */
    public function actu() {
        return $this->render('user/mesActu.html.twig');
    }

    /**
     * @Route("/user/statistiques", name="mesStat")
     */
    public function stat() {
        return $this->render('user/mesStat.html.twig');
    }

}
