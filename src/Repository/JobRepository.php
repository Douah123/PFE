<?php

namespace App\Repository;

use App\Entity\Job;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, 
    private PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Job::class);
    }

//    /**
//     * @return Job[] Returns an array of Job objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Job
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
/**
 * Get published jobs
 *
 * @param int @page
 * @return PaginationInterface
 */
public function findPublished(int $page): PaginationInterface {
    $data = $this->createQueryBuilder('j')
        ->andWhere('j.createdAt <= :now') // Suppose que si la date de création est antérieure ou égale à la date actuelle, l'offre est publiée
        ->setParameter('now', new \DateTime())
        ->orderBy('j.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

        $job = $this->paginatorInterface->paginate($data, $page, 5);
        return $job;
}
/**
 * Get published jobs thank to search
 *
 * @param SearchData $SearchData
 * @return PaginationInterface
 */
public function findBySearch(SearchData $SearchData): PaginationInterface {
    $data = $this->createQueryBuilder('j')
        ->andWhere('j.createdAt <= :now') // Suppose que si la date de création est antérieure ou égale à la date actuelle, l'offre est publiée
        ->setParameter('now', new \DateTime())
        ->orderBy('j.createdAt', 'DESC');

    if (!empty($SearchData->q)) {
        // search on title
        $data = $data
            ->andWhere('j.title LIKE :q')
            ->setParameter('q', "%{$SearchData->q}%");
            // search on description
        $data = $data
        ->orWhere('j.description LIKE :q')
        ->setParameter('q', "%{$SearchData->q}%");  
         // search on location
         $data = $data
         ->orWhere('j.location LIKE :q')
         ->setParameter('q', "%{$SearchData->q}%"); 
         
         $data = $data
        ->innerJoin('j.category', 'c')
        ->orWhere('c.name LIKE :q')
        ->setParameter('q', "%{$SearchData->q}%");
    
    }

    $data = $data
        ->getQuery()
        ->getResult();

    $job = $this->paginatorInterface->paginate($data, $SearchData->page, 5);
    return $job;
}

public function countAlljobs(): int
        {
            return $this->createQueryBuilder('j')
            ->select('COUNT(j)')
            ->getQuery()
            ->getSingleScalarResult();
        }

}
