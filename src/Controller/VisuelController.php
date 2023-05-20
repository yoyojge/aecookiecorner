<?php

namespace App\Controller;

use App\Entity\Visuel;
use App\Form\VisuelType;
use App\Repository\VisuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/visuel')]
class VisuelController extends AbstractController
{
    #[Route('/', name: 'app_visuel_index', methods: ['GET'])]
    public function index(VisuelRepository $visuelRepository): Response
    {
        return $this->render('visuel/index.html.twig', [
            'visuels' => $visuelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_visuel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VisuelRepository $visuelRepository): Response
    {
        $visuel = new Visuel();
        $form = $this->createForm(VisuelType::class, $visuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visuelRepository->save($visuel, true);

            return $this->redirectToRoute('app_visuel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('visuel/new.html.twig', [
            'visuel' => $visuel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_visuel_show', methods: ['GET'])]
    public function show(Visuel $visuel): Response
    {
        return $this->render('visuel/show.html.twig', [
            'visuel' => $visuel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_visuel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visuel $visuel, VisuelRepository $visuelRepository): Response
    {
        $form = $this->createForm(VisuelType::class, $visuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visuelRepository->save($visuel, true);

            return $this->redirectToRoute('app_visuel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('visuel/edit.html.twig', [
            'visuel' => $visuel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_visuel_delete', methods: ['POST'])]
    public function delete(Request $request, Visuel $visuel, VisuelRepository $visuelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visuel->getId(), $request->request->get('_token'))) {
            $visuelRepository->remove($visuel, true);
        }

        return $this->redirectToRoute('app_visuel_index', [], Response::HTTP_SEE_OTHER);
    }
}
