<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }


    public function searchauthor($username)
    {
        return $this->createQueryBuilder('s')
            ->where('s.username=:username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }

    public function searchminmax($min, $max)
    {

        $em = $this->getEntityManager();
        return $em->createQuery('SELECT a from App\Entity\Author a where a.nbrlivre BETWEEN  ?1 and :max')
            ->setParameters(['1' => $min, 'max' => $max])
            ->getResult();
    }

    public function orderbydesc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.username', 'Desc')
            ->getQuery()
            ->getResult();
    }

    public function showbooksauthor($id)
    {

        return $this->createQueryBuilder('a')
            ->join('a.books', 'b')
            ->addSelect('b')
            ->where('b.author=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
