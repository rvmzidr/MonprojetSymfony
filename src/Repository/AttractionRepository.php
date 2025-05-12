<?php

namespace App\Repository;

use App\Entity\Attraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class AttractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attraction::class);
    }

    /**
     * Trouver une attraction par son nom
     *
     * @param string $name
     * @return Attraction|null
     */
    public function findByName(string $name): ?Attraction
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouver les attractions d'une ville spécifique
     *
     * @param int $cityId
     * @return array
     */
    public function findByCity(int $cityId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.city = :cityId')
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les attractions avec un classement supérieur à une certaine valeur
     *
     * @param float $rating
     * @return array
     */
    public function findByRatingGreaterThan(float $rating): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.rating > :rating')
            ->setParameter('rating', $rating)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les attractions avec un filtre sur le nom et la ville
     *
     * @param string|null $name
     * @param int|null $cityId
     * @return array
     */
    public function findByFilter(?string $name, ?int $cityId): array
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if ($name) {
            $queryBuilder->andWhere('a.name LIKE :name')
                         ->setParameter('name', '%' . $name . '%');
        }

        if ($cityId) {
            $queryBuilder->andWhere('a.city = :cityId')
                         ->setParameter('cityId', $cityId);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Compter le nombre d'attractions dans une ville donnée
     *
     * @param int $cityId
     * @return int
     */
    public function countByCity(int $cityId): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->andWhere('a.city = :cityId')
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouver les attractions populaires (note supérieure à un certain seuil)
     *
     * @param float $minRating
     * @return array
     */
    public function findPopularAttractions(float $minRating = 4.0): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.rating >= :minRating')
            ->setParameter('minRating', $minRating)
            ->orderBy('a.rating', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Méthode utilitaire pour créer un `QueryBuilder` de base pour les attractions
     * 
     * @return QueryBuilder
     */
    private function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('a');
    }

    /**
     * Trouver les attractions en vedette (avec isFeatured = true)
     *
     * @return array
     */
    public function findFeaturedAttractions(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isFeatured = :val')
            ->setParameter('val', true)
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les attractions populaires (isPopular = true)
     *
     * @return array
     */
    public function findPopularAttractionsByPopularity(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isPopular = :isPopular')
            ->setParameter('isPopular', true)
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
