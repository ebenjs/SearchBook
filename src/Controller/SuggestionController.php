<?php

namespace App\Controller;

use App\Entity\Suggestion;
use App\Form\SuggestionType;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/suggestion")
 */
class SuggestionController extends AbstractController
{
    /**
     * @Route("/", name="app_suggestion_index", methods={"GET"})
     */
    public function index(SuggestionRepository $suggestionRepository): Response
    {
        return $this->render('suggestion/index.html.twig', [
            'suggestions' => $suggestionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_suggestion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SuggestionRepository $suggestionRepository): Response
    {
        $suggestion = new Suggestion();
        $form = $this->createForm(SuggestionType::class, $suggestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suggestionRepository->add($suggestion, true);

            return $this->redirectToRoute('app_suggestion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suggestion/new.html.twig', [
            'suggestion' => $suggestion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_suggestion_show", methods={"GET"})
     */
    public function show(Suggestion $suggestion): Response
    {
        return $this->render('suggestion/show.html.twig', [
            'suggestion' => $suggestion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_suggestion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Suggestion $suggestion, SuggestionRepository $suggestionRepository): Response
    {
        $form = $this->createForm(SuggestionType::class, $suggestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suggestionRepository->add($suggestion, true);

            return $this->redirectToRoute('app_suggestion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suggestion/edit.html.twig', [
            'suggestion' => $suggestion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_suggestion_delete", methods={"POST"})
     */
    public function delete(Request $request, Suggestion $suggestion, SuggestionRepository $suggestionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suggestion->getId(), $request->request->get('_token'))) {
            $suggestionRepository->remove($suggestion, true);
        }

        return $this->redirectToRoute('app_suggestion_index', [], Response::HTTP_SEE_OTHER);
    }
}
