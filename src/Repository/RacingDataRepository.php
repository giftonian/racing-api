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

    public function fetchRaceResults($race_id): array
    {   
        // $qb = $this->createQueryBuilder('rd');

        // $qb->select('rd.race_id, rd.full_name, rd.race_distance, rd.race_time, rd.age_category')
        // ->addSelect('DENSE_RANK() OVER (ORDER BY rd.race_time) AS overall_placement')
        // ->addSelect('DENSE_RANK() OVER (PARTITION BY rd.age_category ORDER BY rd.race_time) AS age_cat_placement')
        // ->where('rd.race_id = :raceId')
        // ->andWhere('rd.race_distance = :raceDistance')
        // ->setParameter('raceId', 14)
        // ->setParameter('raceDistance', 'long')
        // ->orderBy('overall_placement')
        // ->addOrderBy('age_cat_placement');

        // $results = $qb->getQuery()->getResult();
        //dd($results);

        $qb = $this->createQueryBuilder('rd');

        
        $qb->select('rd.fullName, rd.raceDistance, rd.raceTime, rd.ageCategory')        
        ->where('rd.race = :raceId')
        ->andWhere('rd.raceDistance = :raceDistance')
        ->setParameter('raceId',(int)$race_id)
        ->setParameter('raceDistance', 'long')
        ->orderBy('rd.raceTime');
        //->addOrderBy('age_cat_placement');

        $results = $qb->getQuery()->getResult();

        $placements = [];
        $iter = 1;
        $cat_groups = [];
        foreach ($results as $key => $row) {
            $placements[$row['fullName']] = $row;
            $placements[$row['fullName']]['overall_placement'] = $iter;
            $iter++;

            $cat_groups[$row['ageCategory']][] = $placements[$row['fullName']];
        }
        $iter = 1;
        foreach ($cat_groups as $age_category => $cat_arr) { // age_category
            foreach ($cat_arr as $key => $row) {
                $placements[$row['fullName']]['age_cat_placement'] = $iter;
                $iter++;
            }
            $iter = 1;

        }
        $placements = array_values($placements);
                
        return $placements;

    }
    
}