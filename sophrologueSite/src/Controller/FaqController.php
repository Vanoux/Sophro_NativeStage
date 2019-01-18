<?php

namespace App\Controller;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="myFaq", methods={"GET"})
     */
    public function index(FaqRepository $faqRepository): Response 
    {
        return $this->render('admin/faq/index.html.twig', [
            'faqs' => $faqRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="faq_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response 
    {
        $user = $this->getUser();
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Dis qu'une faq est liée à un utilisateur
            $faq->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faq);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('success', 'Votre question et sa réponse ont bien été crées !');

            return $this->redirectToRoute('myFaq');
        }

        return $this->render('admin/faq/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response 
    {
        return $this->render('admin/faq/show.html.twig', [
            'faq' => $faq,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faq $faq): Response 
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', 'Votre question et sa réponse ont bien été modifiés !');
            return $this->redirectToRoute('myFaq', [
                'id' => $faq->getId(),
            ]);
        }

        return $this->render('admin/faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="faq_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faq $faq): Response 
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faq);
            $entityManager->flush();
        }
        $this->get('session')->getFlashBag()->add('success', 'Votre question et sa réponse ont bien été supprimés !');
        return $this->redirectToRoute('myFaq');
    }
}
