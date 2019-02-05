<?php

namespace App\Controller;

use App\Entity\Conseil;
use App\Form\ConseilType;
use App\Repository\ConseilRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConseilController extends AbstractController
{
   /**
     * @Route("/formulaire", name="form_conseil", methods={"GET","POST"})
     */
    public function create_actu(Request $request, ObjectManager $manager): Response 
    {
        
        $conseil = new Conseil();
        //Création du formulaire
        $form = $this->createForm(ConseilType::class, $conseil);
        $form->handleRequest($request); //Traitement du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            //Télécharge l'image, la convertie et la stocke dans le parameter 
            $image = $form->get('image')->getData();
            if($image !== null){
                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName); 
                $conseil->setImage($imageName);
            }
            //Puis enregistre et envoi dans la BDD
            $manager->persist($conseil);
            $manager->flush();
            // Envoi le message qui confirme l'action
            $this->get('session')->getFlashBag()->add('success', 'Votre conseil a bien été créé !');
            //Fait une redirection
            return $this->redirectToRoute('home', [
                'id' => $conseil->getId()
                ]);
        }
        return $this->render('form/conseil.html.twig', [
            'conseil' => $conseil,
            'formConseil' => $form->createView()
        ]);
    }

}
