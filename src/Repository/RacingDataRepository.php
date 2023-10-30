<?php

namespace App\Repository;

use App\Entity\RacingData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function fetchRaceActors(): array
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