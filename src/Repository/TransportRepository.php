<?php

namespace App\Repository;

use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Transport>
 *
 * @method Transport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transport[]    findAll()
 * @method Transport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }

    /**
     * Trouver un transport par son nom
     *
     * @param string $name
     * @return Transport|null
     */
    public function findByName(string $name): ?Transport
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouver tous les transports disponibles
     *
     * @return Transport[]
     */
    public function findAllTransports(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les transports d'une ville donnée
     *
     * @param int $cityId
     * @return Transport[]
     */
    public function findByCity(int $cityId): array
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.cities', 'c')
            ->andWhere('c.id = :cityId')
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Compter le nombre de transports disponibles dans une ville
     *
     * @param int $cityId
     * @return int
     */
    public function countByCity(int $cityId): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin('t.cities', 'c')
            ->andWhere('c.id = :cityId')
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouver les transports les plus populaires
     *
     * @param float $minRating
     * @return Transport[]
     */
    public function findPopularTransports(float $minRating = 4.0): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.rating >= :minRating')
            ->setParameter('minRating', $minRating)
            ->orderBy('t.rating', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Méthode utilitaire pour créer un `QueryBuilder` de base pour les transports
     * 
     * @return QueryBuilder
     */
    private function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('t');
    }
}
