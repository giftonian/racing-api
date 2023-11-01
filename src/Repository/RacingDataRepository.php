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

    public function fetchRacePlayers(): array
    {
        // $entityManager = $this->getEntityManager();  
        // $dql = "SELECT m, a FROM App\Entity\Race m, 
        // App\Entity\Actor a
        //  ";  
        
        // $qb = $entityManager->createQuery($dql);

        // // example: retrieve the associated EntityManager
        // //$em = $qb->getEntityManager();
        // $dql = $qb->getDql();

        // dd($dql);

    
        // $results = $qb->getResult();

        // dd($results);

        // foreach ($results as $row) {
        //     //echo $book->getTitle() . " : Price = ".$book->getPrice(). "<br/>";
        // }exit;
        $entityManager = $this->getEntityManager();  

        $qb = $entityManager->createQueryBuilder();
        $qb->select('u')
        ->from('App\Entity\RacingData', 'u')
        ->where('u.id = ?1')
        ->orderBy('u.title', 'ASC')
        ->setParameter(1, 40); // Sets ?1 to 40, and thus we will fetch a user with u.id = 100


        
        
        //->where('u.id = ?1')
        //->orderBy('u.name', 'ASC');

        $res = $qb->getQuery()->getResult();
        foreach($res as $race) {
            echo $race->getTitle();           
        }exit;



        return [];//iterator_to_array($paginator);

    }
    public function fetchRaceCollections(): array
    {
        $response = ['calling', 'hello fetch race collections'];
        //$entityManager = $this->getEntityManager();  
        //$dql = "SELECT * FROM App\Entity\Race m";
        
        // $sql = "SELECT r.race_id AS race_id, race.title AS race_title, race.race_date,
        //     TIME_FORMAT(SEC_TO_TIME(AVG(CASE WHEN r.race_distance = :medium THEN TIME_TO_SEC(r.race_time) ELSE NULL END)), :timeFormat) AS avg_time_medium,
        //     TIME_FORMAT(SEC_TO_TIME(AVG(CASE WHEN r.race_distance = :long THEN TIME_TO_SEC(r.race_time) ELSE NULL END)), :timeFormat) AS avg_time_long
        //     FROM racing_data r
        //     LEFT JOIN race ON r.race_id = race.id
        //     WHERE r.race_distance IN (:distances)
        //     GROUP BY race_id, race_title, race_date";
        //     //echo $sql;

        // $em = $this->getEntityManager();
        // $query = $em->createNativeQuery($sql,new \Doctrine\ORM\Query\ResultSetMapping());

        // $query->setParameter('medium', 'medium');
        // $query->setParameter('long', 'long');
        // $query->setParameter('timeFormat', '%H:%i:%s');
        // $query->setParameter('distances', ['medium', 'long']);

        // $results = $query->getResult();

        $qb = $this->createQueryBuilder('a');

        $qb->select('a.id as race_id', 'a.title as race_title', 'a.race_date')
            ->addSelect('TIME_FORMAT(sectotime(AVG(CASE WHEN b.race_distance = :medium THEN timetosec(b.race_time) ELSE :no_time END)), :format) AS avg_time_medium')
            ->addSelect('TIME_FORMAT(sectotime(AVG(CASE WHEN b.race_distance = :long THEN timetosec(b.race_time) ELSE :no_time END)), :format) AS avg_time_long')            
            //->from('App\Entity\Race', 'a')           
            ->leftJoin('App\Entity\RacingData', 'b', 'WITH', 'a.id = b.race_id')            
            ->where($qb->expr()->in('b.race_distance', ':distances'))
            ->groupBy('a.id')
            ->setParameter('medium', 'medium')
            ->setParameter('long', 'long')
            ->setParameter('no_time', '00:00:00')
            ->setParameter('format', '%H:%i:%s')
            ->setParameter('distances', ['medium', 'long']);

        // $qb->select('a.id as race_id', 'r.title as race_title')
        //     ->addSelect('TIME_FORMAT(sectotime(AVG(CASE WHEN b.race_distance = :medium THEN timetosec(b.race_time) ELSE :no_time END)), :format) AS avg_time_medium')
        //     ->addSelect('TIME_FORMAT(sectotime(AVG(CASE WHEN b.race_distance = :long THEN timetosec(b.race_time) ELSE :no_time END)), :format) AS avg_time_long')
        //     ->leftJoin('App\Entity\Race', 'r', 'WITH', 'a.id = r.id')
        //     ->leftJoin('App\Entity\RacingData', 'b', 'WITH', 'a.id = b.race_id')
        //     ->where($qb->expr()->in('b.race_distance', ':distances'))
        //     ->groupBy('a.id')
        //     ->setParameter('medium', 'medium')
        //     ->setParameter('long', 'long')
        //     ->setParameter('no_time', '00:00:00')
        //     ->setParameter('format', '%H:%i:%s')
        //     ->setParameter('distances', ['medium', 'long']);

  

        
        $results = $qb->getQuery()->getResult();
        dd($results);


        return $response;

    }

//    /**
//     * @return Race[] Returns an array of Race objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Race
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}