<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    
    
    
    #[Route('/', name: 'app_adresse_index', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {
        
        $user = $this->getUser();
        $userId = $user->getId();
        $criteria = ['user' => $userId];
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findBy($criteria),
        ]);
    }




    #[Route('/new', name: 'app_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdresseRepository $adresseRepository): Response
    {
        $adresse = new Adresse();
        // $form = $this->createForm(AdresseType::class, $adresse);
        // $form->handleRequest($request);

        $form = $this ->createFormBuilder( $adresse )->getForm();
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            // dd($_POST["adresse"]);
            $adresse->setNomAdresse($_POST["adresse"]["nomAdresse"]);
            $adresse->setPrenomAdresse($_POST["adresse"]["prenomAdresse"]);
            $adresse->setTypeAdresse($_POST["adresse"]["typeAdresse"]);
            $adresse->setRueAdresse($_POST["adresse"]["rueAdresse"]);
            $adresse->setCpAdresse($_POST["adresse"]["cpAdresse"]);
            $adresse->setVilleAdresse($_POST["adresse"]["villeAdresse"]);
            $user = $this->getUser();
            $adresse->setUser($user);

            $adresseRepository->save($adresse, true);

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
    {
        // $form = $this->createForm(AdresseType::class, $adresse);
        // $form->handleRequest($request);

        $form = $this ->createFormBuilder( $adresse )->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $adresse->setTypeAdresse($_POST["adresse"]["typeAdresse"]);
            $adresse->setRueAdresse($_POST["adresse"]["rueAdresse"]);
            $adresse->setCpAdresse($_POST["adresse"]["cpAdresse"]);
            $adresse->setVilleAdresse($_POST["adresse"]["villeAdresse"]);
            $user = $this->getUser();
            $adresse->setUser($user);

            $adresseRepository->save($adresse, true);

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $adresseRepository->remove($adresse, true);
        }

        return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
    }
}
