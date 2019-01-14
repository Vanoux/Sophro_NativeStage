<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
        //Par défaut l'utilisateur à le role user
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
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
            ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        
    }


// ******************* Controllers pour oubli du mot de passe = ne fonctionne pas ****************************    
    // /**
    //  * @Route("/forgottenPassword", name="user_forgotten_password")
    //  */
    // public function forgottenPassword( Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    // {
    //     if($request->isMethod('POST')){

    //         $mail = $request->request->get('mail');

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $user = $entityManager->getRepository(User::class)->findOneByMail($mail);
    //          /* @var $user User */
 
    //          if ($user === null) {
    //             $this->addFlash('danger', 'Email Inconnu');
    //             return $this->redirectToRoute('home');
    //         }
    //         $token = $tokenGenerator->generateToken();


    //         try{
    //             $user->setResetToken($token);
    //             $entityManager->flush();
    //         } catch (\Exception $exception) {
    //             $this->addFlash('warning', $exception->getMessage());
    //             return $this->redirectToRoute('home');
    //         }

    //         $url = $this->generateUrl('user_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
 
    //         $message = (new \Swift_Message('Forgot Password'))
    //         ->setFrom('vanessa.roux891@orange.fr')
    //         ->setTo($user->getEmail())
    //         ->setBody(
    //             "blablabla voici le token pour reseter votre mot de passe : " . $url,
    //             'text/html'
    //         );

    //     $mailer->send($message);

    //     $this->addFlash('notice', 'Mail envoyé');

    //     return $this->redirectToRoute('home');
    //     }
        

    //     return $this->render('security/forgotten_password.html.twig');
    // }


    // /**
    //  * @Route("/reset_password/{token}", name="user_reset_password")
    //  */
    // public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    // {

    //     if ($request->isMethod('POST')) {
    //         $entityManager = $this->getDoctrine()->getManager();
 
    //         $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
    //         /* @var $user User */
 
    //         if ($user === null) {
    //             $this->addFlash('danger', 'Token Inconnu');
    //             return $this->redirectToRoute('home');
    //         }
 
    //         $user->setResetToken(null);
    //         $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
    //         $entityManager->flush();
 
    //         $this->addFlash('notice', 'Mot de passe mis à jour');
 
    //         return $this->redirectToRoute('home');

    //     } else {
 
    //         return $this->render('security/reset_password.html.twig', ['token' => $token]);
    //     }
    // }



}
