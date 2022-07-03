<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditCategoryFormType;

#[Route(path: '/admin')]
class CategoryController extends AbstractController
{
    #[Route('/category', name: 'admin_category')]
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $categoryRepository->findAll();

        $pagination = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/category/index.html.twig', compact('pagination'));
    }

    #[Route('/category/show/{id}', name: 'admin_show_category')]
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

            return $this->render('admin/category/show.html.twig', compact('paginationNeedleBooks', 'currentCategory'));
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

        return $this->render('admin/category/show.html.twig', compact('paginationNeedleBooks', 'currentCategory'));
    }

    #[Route(path: '/category/create', methods: ["GET", "POST"], name: 'admin_category_create')]
    #[Route(path: '/category/edit/{id}', methods: ["GET", "POST"], name: 'admin_category_edit', requirements: ["id" => "^\d+"])]
    public function edit(CategoryRepository $categoryRepository, Request $request, $id = null): Response
    {
        if (null === $id) {
            $category = new Category();
        } else {
            $category = $categoryRepository->find($id);
        }

        $form = $this->createForm(EditCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryRepository->add($category, true);

            $this->addFlash(
                'success',
                'Category whith id: '. $id . ', updated successfuly!'
            );

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route(path: '/delete/category/{id}', methods: ["POST"], name: 'admin_category_delete', requirements: ["id" => "^\d+"])]
    public function delete(CategoryRepository $categoryRepository, $id): Response
    {
        $deletedCategory = $categoryRepository->find($id);

        if (null === $deletedCategory) {

            throw $this->createNotFoundException(
                'Not category found for id: ' . $id
            );

        }

        $removedBooks = $deletedCategory->getBooks();

        foreach ($removedBooks as $removedBook) {
            $deletedCategory->removeBook($removedBook);
        }

        $categoryRepository->remove($deletedCategory, true);

        $this->addFlash(
            'success',
            "Category whith id: {$id}, deleted successfuly!"
        );

        return $this->redirectToRoute('admin_category');
    }
}
