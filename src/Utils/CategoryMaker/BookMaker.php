<?php

namespace App\Utils\CategoryMaker;

class BookMaker
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        
    }

    public function add(array $book): void
    {
        $doctrine = $this->doctrine;

        $newBook = new \App\Entity\Book();

        array_key_exists('title', $book)
            ? $newBook->setTitle($book['title'])
            : $newBook->setTitle('');

        array_key_exists('isbn', $book)
            ? $newBook->setIsbn($book['isbn'])
            : $newBook->setIsbn('');

        array_key_exists('pageCount', $book)
            ? $newBook->setPageCount($book['pageCount'])
            : $newBook->setPageCount('');
        
        array_key_exists('thumbnailUrl', $book)
            ? $newBook->setThumbnailUrl($book['thumbnailUrl'])
            : $newBook->setThumbnailUrl(false);

        array_key_exists('shortDescription', $book)
            ? $newBook->setShortDescription($book['shortDescription'])
            : $newBook->setShortDescription('');

        array_key_exists('longDescription', $book)
            ? $newBook->setLongDescription($book['longDescription'])
            : $newBook->setLongDescription('');

        array_key_exists('status', $book)
            ? $newBook->setStatus($book['status'])
            : $newBook->setStatus('');

        if (array_key_exists('publishedDate', $book)) {
            $oldFormatDate = date($book["publishedDate"]['$date']);
            $date = new \DateTime($oldFormatDate);
            $newBook->setPublishedDate($date);
        }

        // save book image to "app/asset/images/" and set image path to table 'book'
        if ($newBook->getThumbnailUrl() != null) {
            $image = new \App\Utils\CategoryMaker\SaveBookImage($newBook);
            $image->add();
            $newBook->setImage($image->getNameImage());
        } else {
            $newBook->setImage('default-book.jpg');
        }

        // add new category or set exists one (OneToMany)
        $categoryMaker = new \App\Utils\CategoryMaker\CategoryMaker($doctrine);
        $category = $categoryMaker->setCategory($book['categories']);
        $newBook->setCategory($category);

        // add new authors or set exists ones (ManyToMany)
        array_map(function ($author) use ($doctrine, $newBook) {
            $newAuthor = new \App\Utils\CategoryMaker\AuthorMaker($doctrine);
            $author = $newAuthor->setAuthor($author);
            $newBook->addAuthor($author);
            $em = $doctrine->getManager();
            $em->persist($author);
        }, $book['authors']);

        $em = $doctrine->getManager();
        $em->persist($category);
        $em->persist($newBook);
        $em->flush();
    }
}