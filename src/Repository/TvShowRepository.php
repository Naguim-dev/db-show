<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tvshow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tvshow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tvshow[]    findAll()
 * @method Tvshow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

    public function findWithCollections($id)

    {
        $queryBuilder = $this->createQueryBuilder('tvShow');
        $queryBuilder->where(
            $queryBuilder->expr()->eq('tvShow.id', $id)
        );

        $queryBuilder->leftjoin('tvShow.categories', 'category');
        $queryBuilder->addSelect('category');

        $queryBuilder->leftjoin('tvShow.characters', 'character');
        $queryBuilder->addSelect('character');

        $queryBuilder->leftjoin('character.actors', 'actor');
        $queryBuilder->addSelect('actor');

        $queryBuilder->leftjoin('tvShow.seasons', 'season');
        $queryBuilder->addSelect('season');

        $queryBuilder->leftjoin('season.episodes', 'episode');
        $queryBuilder->addSelect('episode');

        $queryBuilder->addOrderBy('character.name');
        $queryBuilder->addOrderBy('actor.firstName');

        $query = $queryBuilder->getQuery();
        return $query->getOneOrNullResult();

    }

    public function findByTitle($search)
    {
        $queryBuilder = $this->createQueryBuilder('tvShow');
        $queryBuilder->leftJoin('tvShow.characters', 'character');
        $queryBuilder->leftJoin('character.actors', 'actor');
        if (!empty($search)) {
            $queryBuilder->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('tvShow.title', ':search'),
                    $queryBuilder->expr()->like('character.name', ':search'),
                    $queryBuilder->expr()->like('actor.firstName', ':search')
                )
            );
            $queryBuilder->setParameter('search', "%$search%");
        }
        
        $queryBuilder->addOrderBy('tvShow.title');

        $query = $queryBuilder->getQuery();
        return $query->getResult();

    }

}