<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditAuthorFormType;

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

    #[Route(path: '/author/create', methods: ["POST", "GET"], name: 'admin_author_create')]
    #[Route(path: '/author/edit/{id}', methods: ["POST", "GET"], name: 'admin_author_edit', requirements: ["id" => "^\d+"])]
    public function edit(AuthorRepository $authorRepository, Request $request, $id = null): Response
    {
        if (null === $id) {
            $author = new Author();
        } else {
            $author = $authorRepository->find($id);
        }

        $form = $this->createForm(EditAuthorFormType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $authorRepository->add($author, true);

            $this->addFlash(
                'success',
                'Author is created/updated successfuly'
            );

            return $this->redirectToRoute('admin_author');
        }

        return $this->render('/admin/author/edit.html.twig', [
            'form' => $form->createView()
        ]);

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
 
        $this->addFlash(
            'success',
            'Author whith id: ' . $id . ', removed successfuly!');

        return $this->redirectToRoute('admin_author');
    }
    
    #[Route('/author/show{id}', name: 'admin_show_author')]
    public function show(AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);
    
        return $this->render('admin/author/show.html.twig', compact('author'));
    }
}
