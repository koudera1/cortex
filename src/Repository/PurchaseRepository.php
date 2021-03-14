<?php

namespace App\Repository;

use App\Entity\Purchase;
use Cassandra\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Customer;
use \DateTime;

/**
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function findMostSpendingCustomers():array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c.firstname,c.lastname, sum(p.totalPrice) as moneySpent
                FROM App\Entity\Purchase p
                INNER JOIN p.customer c
                WHERE p.purchaseDate BETWEEN :first AND :second
                GROUP BY c.id
                ORDER BY moneySpent DESC")
            ->setParameter("first", date_sub(new DateTime(), date_interval_create_from_date_string('65 months')))
            ->setParameter("second", new DateTime())
            ->setMaxResults(10)->getArrayResult();
    }
}
