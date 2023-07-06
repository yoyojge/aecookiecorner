<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\Adresse;
use App\Form\FactureType;
use App\Repository\AdresseRepository;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        $criteria = ['user' => $userId];
        $commandes = $commandeRepository->findBy($criteria);

        $factures = [];
        foreach($commandes as $key => $commande){
            $criteria2 = ['commande' => $commande];
            if (!empty($factureRepository->findOneBy($criteria2))){
                $factures[] =  $factureRepository->findOneBy($criteria2);
            }
        }
        
        
        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }



    
    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture, CommandeRepository $commandeRepository,  AdresseRepository $adresseRepositor): Response
    {
        //recuperer le montant de la commande
        $commande = $facture->getCommande();
        $idCommande = $commande->getId();
        $montantCommande = $commande->getTotalCommande();
        //recuperer l'adresse
        $adresseFacturation = $facture->getAdresse();

        $adresseF = [];
        $rueAdresseFacturation = $adresseFacturation->getRueAdresse();
        $adresseF['rueAdresseFacturation'] = $rueAdresseFacturation;

        $villeAdresseFacturation = $adresseFacturation->getVilleAdresse();
        $adresseF['villeAdresseFacturation'] = $villeAdresseFacturation;

        $cpAdresseFacturation = $adresseFacturation->getCpAdresse();
        $adresseF['cpAdresseFacturation'] = $cpAdresseFacturation;

        $nomAdresseFacturation = $adresseFacturation->getNomAdresse();
        $adresseF['nomAdresseFacturation'] = $nomAdresseFacturation;

        $prenomAdresseFacturation = $adresseFacturation->getPrenomAdresse();
        $adresseF['prenomAdresseFacturation'] = $prenomAdresseFacturation;
        
        // dd($adresseFacturation->getRueAdresse(), $adresseFacturation->getVilleAdresse(), $adresseFacturation->getCpAdresse());
        
        
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
            'idCommande' => $idCommande,
            'montantCommande' => $montantCommande,
            'adresseF' => $adresseF,
        ]);
    }






    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
