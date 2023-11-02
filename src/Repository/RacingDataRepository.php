<?php

namespace App\Repository;

use App\Entity\RacingData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\DateFormat;

/**
 * @extends ServiceEntityRepository<Race>
 *
 * @method Race|null find($id, $lockMode = null, $lockVersion = null)
 * @method Race|null findOneBy(array $criteria, array $orderBy = null)
 * @method Race[]    findAll()
 * @method Race[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacingDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacingData::class);
    }

    public function save(RacingData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RacingData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    } 
    
}