<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function save(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   public function findThreeLast(): array
   {
       return $this->createQueryBuilder('e')
           ->orderBy('e.date_emprunt', 'DESC')
           ->setMaxResults(3)
           ->getQuery()
           ->getResult()
       ;
   }

   public function allSecondEmprunteurEmprunt(): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.emprunteur', 'em')
            ->andWhere('em.id = :id')
            ->setParameter('id', 2)
           ->orderBy('e.date_emprunt', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function thirdLivreEmprunt(): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.livre', 'l')
            ->andWhere('l.id = :id')
            ->setParameter('id', 3)
           ->orderBy('e.date_emprunt', 'DESC')
           ->getQuery()
           ->getResult()
       ;
   }


   public function findNotReturn(): array
   {
       return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour IS NULL')
           ->orderBy('e.date_emprunt', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByEmprunteur(Emprunteur $emprunteur): array
      {
          return $this->createQueryBuilder('e')
            ->innerJoin('e.emprunteur', 'er')
              ->andWhere('er.id = :emprunteurId')
              ->setParameter('emprunteurId', $emprunteur->getId())
              ->orderBy('e.date_emprunt', 'ASC')
              ->getQuery()
              ->getResult()
          ;
      }
//    /**
//     * @return Emprunt[] Returns an array of Emprunt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
