<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Form\ResettingType;




class ResettingController extends AbstractController
{

    /**
     * @Route("/requete", name="request_resetting")
     */
    public function request(Request $request, ObjectManager $manager, Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        

        // création d'un formulaire, afin que l'internaute puisse renseigner son mail
        $form = $this->createFormBuilder()

                ->add('mail', EmailType::class, [
                        'constraints' => [
                            new Email(),
                            new NotBlank()
                        ]
                    ])
                ->getForm();
                
        //Permet au formulaire d'analyser la requête = traitement du formulaire
        $form->handleRequest($request);


        //Si le formulaire est soumit et que tout les champs sont valide
        if($form->isSubmitted() && $form->isValid()){

            //****** check le mail de l'utilisateur *****
            $user = $manager->getRepository(User::class)->findBy([
                'mail' => $form->getData('mail')
            ]);

            var_dump($user);
            // Envoi le message en cas d'erreur
            if(!$user){
                $this->get('session')->getFlashBag()->add('warning', 'Votre email n\'existe pas !');
                return $this->redirectToRoute('request_resetting');
            }
                
            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());


            //Fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist();
            //Et le sauvegarde dans la bdd
            $manager->flush();


            //On utilise le service Mailer créé précédemment
            $bodyMail = $mailer->createBodyMail('resetting/mail.html.twig', [
                'user' => $user
            ]);
            //Envoi le message de renouvellement
            $mailer->sendMessage('email@renouvellement.fr', $user->getMail(), 'Renouvellement du mot de passe !', $bodyMail);
            //Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Un mail vient de vous être envoyé ! Le lien de renouvellement ne sera valide que 24h !');
                    return $this->redirectToRoute('security_login');
            
        }

        return $this->render('resetting/request.html.twig', [
            'form' => $form->createView()
        ]);
    }


    private function isRequestInTime(\Datetime $passwordRequestedAt = NULL)
    {
        if($passwordRequestedAt === NULL)
        {
            return false;
        }
        // si supérieur à 10min, retourne false
        // sinon retourne false
        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();
        $daySeconds = 60 * 10;
        $response = $interval > $daySeconds ? false : $response = true;
        return $response;

    }

    /**
     * @Route("/{id}/{token}", name="resetting")
     */
    public function resetting(User $user, $token, ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 10 minutes
        if($user->getToken() === NULL || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }

        //Création du formulaire
        $form = $this->createForm(ResettingType::class, $user);
        //Traitement du formulaire
        $form->handleRequest($request);

        //Si le formulaire est soumit et que tout les champs sont valide
        if($form->isSubmitted() && $form->isValid()){
            
                //Avant de sauvegarder l'utilisateur = hash du mdp
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);

            // réinitialisation du token à null pour qu'il ne soit plus réutilisable
            $user->setToken(NULL);
            $user->setPasswordRequestedAt(NULL);

            //Puis fait persister dans le temps l'utilisateur et le prépare pour la bdd
            $manager->persist($user);
            //Et le sauvegarde dans la bdd
            $manager->flush();

            //Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre mot de passe vient d\'être renouvelé !');
                return $this->redirectToRoute('security_login');
            }

        return $this->render('resetting/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}