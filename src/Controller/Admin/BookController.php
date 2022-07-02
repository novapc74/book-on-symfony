<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\EditBookFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

#[Route(path:'/admin')]
class BookController extends AbstractController
{
    #[Route('/book', name: 'admin_book')]
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $books = $bookRepository->findAll();

        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('admin/book/index.html.twig', compact('pagination'));
    }

    #[Route('/show/{id}', name: 'admin_show_book')]
    public function show(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request, $id): Response
    {
        $book = $bookRepository->find($id);
        $date = $book->getPublishedDate();
        if (null != ($date)) {
            $time = $date->format('d:m:Y');
        } else {
            $time = 'data is missing.';
        }

        $category = $book->getCategory();
        $categoryBooks = $bookRepository->findBy(['category' => $category]);

        $paginateCategories = $paginator->paginate(
            $categoryBooks,
            $request->query->getInt('page', 1),
            4
        );

        if (null == $book) {
            throw $this->createNotFoundException(
                'No book found for id: '. $id
            );
        }
        return $this->render('admin/book/show.html.twig', compact('book', 'time', 'paginateCategories'));
    }

    #[Route(path: 'admin/book/edit/{id}', methods: ['GET', 'POST'], name: 'admin_book_edit', requirements: ['id' => '\d+'])]
    #[Route(path: 'admin/book/create', methods: ['GET', 'POST'], name: 'admin_book_create')]
    public function editBook(ManagerRegistry $doctrine, BookRepository $bookRepository, Request $request, $id = null): Response
    {
        if ($id) {
            $book = $bookRepository->find($id);

        } else {
            $book = new Book();
        }

        $form = $this->createForm(EditBookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            if ($book->getThumbnailUrl() != null) {
                $saveImage = new \App\Utils\CategoryMaker\SaveBookImage($book);
                $saveImage->add();
                $book->setImage($saveImage->getNameImage());
            } else {
                $book->setImage('default-book.jpg');
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('admin_book');
        }

        return $this->renderForm('admin/book/edit.html.twig', ['form' => $form]);
    }
}