<?php

namespace App\Utils\CategoryMaker;

class CategoryMaker
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        
    }
    /**
     * @param  array $categories
     * @return \App\Entity\Category object
     */
    public function setCategory(array $categories)
    {
        [$name, $subname] = $categories == []
            ? ['New', '']
            : [array_shift($categories), implode(', ', $categories)];

        $em = $this->doctrine->getManager();
        $category = $em->getRepository(\App\Entity\Category::class)->findOneBy(
            [
            'name' => $name,
            'subname' => $subname
            ]
        );

        if (null === $category) {
            $category = new \App\Entity\Category();
            $category->setName($name);
            $category->setSubname($subname);
        }
        return $category;
    }
}