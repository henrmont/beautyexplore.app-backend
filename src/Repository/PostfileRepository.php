<?php

namespace App\Repository;

use App\Entity\Postfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Postfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postfile[]    findAll()
 * @method Postfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postfile::class);
    }

    // /**
    //  * @return Postfile[] Returns an array of Postfile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Postfile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Post[] Returns an array of post objects
     */
    public function getPostFiles($post)
    {
        $qb = $this->createQueryBuilder('postfile');

        $qb
            ->select('
                postfile
            ')
            ->where('postfile.post_id = :post')
            // ->where('imposto.plantao_id IN(:plantoes)')
            ->setParameter('post',$post)
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
