<?php

namespace App\Repository;

use App\Entity\LoyaltyCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog loyalty card information.
 * @method LoyaltyCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoyaltyCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoyaltyCard[]    findAll()
 * @method LoyaltyCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoyaltyCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoyaltyCard::class);
    }

    /**
     * @return LoyaltyCard
     */
    public function findLowestLoyaltyCard($type): LoyaltyCard
    {
        $minNum = 0;
        try {
            $minNum = $this->createQueryBuilder('l')
                ->select('MIN(l.number)')
                ->where('l.customer IS NULL')
                ->where('l.type = ?1')
                ->setParameter(1, $type)
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        try {
            return $this->createQueryBuilder('l')
                ->where('l.number = ?1')
                ->setParameter(1, $minNum)
                ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    /**
     * @return LoyaltyCard[]
     */
    public function searchByAttributes($attributes, $bool1, $bool2): array
    {
        if($attributes)
        $query = $this->createQueryBuilder('l')->join('l.customer', 'c', 'WITH', 'c = l.customer');
        if($bool1 and $bool2)
            return $query
                ->select('c.firstname,c.lastname,c.address,c.email,c.telephone,c.registrationDate')
                ->where('c.firstname = ?1')
                ->andWhere('c.lastname = ?2')
                ->andWhere('l.number = ?3')
                ->setParameter(1, $attributes['firstname'])
                ->setParameter(2, $attributes['lastname'])
                ->setParameter(3, $attributes['loyaltyCard'])
                ->getQuery()->getArrayResult();

        if($bool1)
            return $query
                ->select('c.firstname,c.lastname,c.address,c.email,c.telephone,c.registrationDate')
                ->where('c.firstname = ?1')
                ->andWhere('l.number = ?2')
                ->setParameter(1, $attributes['firstname'])
                ->setParameter(2, $attributes['loyaltyCard'])
                ->getQuery()->getArrayResult();

        if($bool2)
            return $query
                ->select('c.firstname,c.lastname,c.address,c.email,c.telephone,c.registrationDate')
                ->where('c.lastname = ?1')
                ->andWhere('c.loyaltyCard = ?2')
                ->setParameter(1, $attributes['lastname'])
                ->setParameter(2, $attributes['loyaltyCard'])
                ->getQuery()->getArrayResult();

        return $query
            ->select('c.firstname,c.lastname,c.address,c.email,c.telephone,c.registrationDate')
            ->andWhere('l.number = ?1')
            ->setParameter(1, $attributes['loyaltyCard'])
            ->getQuery()->getArrayResult();

    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countAssignedCards()
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->where('l.customer IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
}