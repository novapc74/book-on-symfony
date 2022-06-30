<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $categoryRepository->findAll();

        $pagination = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('main/category/index.html.twig', compact('pagination'));
    }

    #[Route('/category/show/{id}', name: 'show_category')]
    public function show(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request, $id): Response
    {
        $currentCategory = $categoryRepository->find($id);

        $name = $currentCategory->getName();
        $subname = $currentCategory->getSubname();
        $books = $currentCategory->getBooks();

        $needleCategories = $categoryRepository->findByNameField($name);

        if ($subname == '') {

            $paginationNeedleBooks = $paginator->paginate(
                $books,
                $request->query->getInt('page', 1),
                4
            );

            return $this->render('main/category/show.html.twig', compact('paginationNeedleBooks', 'currentCategory'));
        }

        $needleSubcategories = [];
        $subcategories = explode(', ', $subname);

        foreach ($subcategories as $subcategory) {
            $needleSubcategory = $categoryRepository->findAllSubcategories($subcategory);
            foreach ($needleSubcategory as $currenSubcategory) {
                $needleSubcategories[] = $currenSubcategory;
            }
        }

        $needleBooks = array_map(function ($category) {
            $books = $category->getBooks();
            foreach ($books as $book) {
                return $book;
            }
        }, $needleSubcategories);

        $paginationNeedleBooks = $paginator->paginate(
            $needleBooks,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('main/category/show.html.twig', compact('paginationNeedleBooks', 'currentCategory'));
    }

}
