<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'tags' => $tagRepository->findLimit(3),
        ]);
    }
}
