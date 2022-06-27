<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param  string $name
     * @param  string $subName
     * @return Category[] Returns an array of Category objects
     */
    public function findByNameField($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.subname = :val')
            ->orWhere('c.name = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    
   public function findAllSubcategories($subcategory): array
   {
        return $this->createQueryBuilder('c')
            ->where('c.subname = :val')
            ->orWhere('c.name = :val')
            ->setParameter('val', $subcategory)
            ->getQuery()
            ->getResult()
        ;
   }
}
