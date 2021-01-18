<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function findAllOrderByLabel()
    {
        $queryBuilder = $this->createQueryBuilder('category');
        $queryBuilder->orderBy('category.label', 'asc');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findOneWithTvShows($id)
    {
        $queryBuilder = $this->createQueryBuilder('category');
        $queryBuilder->where(
            $queryBuilder->expr()->eq('category.id', $id)
        );
        $queryBuilder->leftjoin('category.tvShows', 'tvShow');
        $queryBuilder->addSelect('tvShow');

        $queryBuilder->addOrderBy('tvShow.title', 'asc');

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();
    }
}
