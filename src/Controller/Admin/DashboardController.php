<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/admin')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_secured_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/author/show{id}', name: 'show_author')]
    public function show(AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);
    
        return $this->render('main/author/show.html.twig', compact('author'));
    }
}