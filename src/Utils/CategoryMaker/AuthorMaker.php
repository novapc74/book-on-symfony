<?php

namespace App\Utils\CategoryMaker;

class AuthorMaker
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        
    }
    /**
     * @param  string $newAuthor
     * @return \App\Entity\Author object
     */
    public function setAuthor($newAuthor)
    {
        $em = $this->doctrine->getManager();
        $author = $em->getRepository(\App\Entity\Author::class)->findOneBy(['name' => $newAuthor]);

        if (null === $author) {
            $author = new \App\Entity\Author();
            $author->setName($newAuthor);
        }
        return $author;
    }
}