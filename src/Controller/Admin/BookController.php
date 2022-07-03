<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\EditBookFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
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

    #[Route(path: '/book/create', methods: ['GET', 'POST'], name: 'admin_book_create')]
    #[Route(path: '/book/edit/{id}', methods: ['GET', 'POST'], name: 'admin_book_edit', requirements: ['id' => '\d+'])]
    public function edit(BookRepository $bookRepository, ManagerRegistry $doctrine, Request $request, $id = null): Response
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
            if (null === $book->getThumbnailUrl()) {
                $book->setImage('default-book.jpg');
            } else {
                $saveImage = new \App\Utils\CategoryMaker\SaveBookImage($book);
                $saveImage->add();
                $book->setImage($saveImage->getNameImage());
            }
                $entityManager = $doctrine->getManager();
                $entityManager->flush();
            $this->addFlash('success', 'Book whith id: '. $id . ', updated successfuly!');

            return $this->redirectToRoute('admin_book');
        }

        return $this->renderForm('admin/book/edit.html.twig', ['form' => $form]);
    }

    #[Route('/book/delete/{id}', name: 'admin_book_delete', requirements: ["id" => "^\d+"])]
    public function delete(BookRepository $bookRepository, $id): Response
    {
        $deletedBook = $bookRepository->find($id);

        if (null === $deletedBook) {
            throw $this->createNotFoundException(
                'Not book found for id: ' . $id
            );
        }

        $deletedBook->setCategory(null);
        $removedAuthors = $deletedBook->getAuthors();
        foreach ($removedAuthors as $removedAuthor) {
            $deletedBook->removeAuthor($removedAuthor);
        }

        $bookRepository->remove($deletedBook, true);

        $this->addFlash('success', 'Book whith id: '. $id . ', deleted successfuly!');
        return $this->redirectToRoute('admin_book');
    }

    #[Route('/show/{id}', name: 'admin_book_show')]
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
}