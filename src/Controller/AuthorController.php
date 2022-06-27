<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $authorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $authors = $authorRepository->findAll();
    
        $pagination = $paginator->paginate(
            $authors,
            $request->query->getInt('page', 1),
            8
        );
    
        return $this->render('author/index.html.twig', compact('pagination'));
    }

    #[Route('/author/show{id}', name: 'show_author')]
    public function show(AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);
    
        return $this->render('author/show.html.twig', compact('author'));
    }
}
