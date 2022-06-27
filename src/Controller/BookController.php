<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $books = $bookRepository->findAll();

        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('book/index.html.twig', compact('pagination'));
    }

    #[Route('/show/{id}', name: 'show_book')]
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
        return $this->render('book/show.html.twig', compact('book', 'time', 'paginateCategories'));
    }
}
