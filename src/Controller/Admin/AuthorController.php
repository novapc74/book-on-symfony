<?php

namespace App\Controller\Admin;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

#[Route(path: '/admin')]
class AuthorController extends AbstractController
{
    #[Route('/author', name: 'admin_author')]
    public function index(AuthorRepository $authorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $authors = $authorRepository->findAll();
    
        $pagination = $paginator->paginate(
            $authors,
            $request->query->getInt('page', 1),
            8
        );
    
        return $this->render('admin/author/index.html.twig', compact('pagination'));
    }

    #[Route('/author/show{id}', name: 'admin_show_author')]
    public function show(AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);
    
        return $this->render('admin/author/show.html.twig', compact('author'));
    }

    #[Route(path: '/author/delete/{id}', methods: ["POST"], name: 'admin_author_delete', requirements: ["id" => "^\d+"])]
    public function delete(AuthorRepository $authorRepository, $id): Response
    {
        $deletedAuthor = $authorRepository->find($id);

        if (null === $deletedAuthor) {
            throw $this->createNotFoundException(
                'Author for id:' . $id
            );
        }

        $authorRepository->remove($deletedAuthor, true);
 
        $this->addFlash('success', 'Author whith id: ' . $id . ', removed successfuly!');

        return $this->redirectToRoute('admin_author');
    }
}
