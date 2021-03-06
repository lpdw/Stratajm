<?php

namespace CommonBundle\Repository;

/**
 *  MemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MemberRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByArg($order = 'desc', $offset = 0, $limit = 10){
        $result = $this->createQueryBuilder('m')
            ->select('m')
//            ->leftJoin('CommonBundle\Entity\Membership','ms', \Doctrine\ORM\Query\Expr\Join::ON, 'ms.member = m')
            ->orderBy('m.id', $order)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function countAll(){
        $result = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    public function findAll(){
        $result = $this->createQueryBuilder('m')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findBySearch($search, $order = 'desc', $offset = 0, $limit = 10){

        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.id', $order)
            ->where('m.firstName LIKE :search')
            ->orWhere('m.lastName LIKE :search')
            ->orWhere('m.email LIKE :search')
            ->orWhere('m.telNum LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();


        return $result;
    }

    public function countAllBySearch($search){
        $result = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->orderBy('m.id', 'desc')
            ->where('m.firstName LIKE :search')
            ->orWhere('m.lastName LIKE :search')
            ->orWhere('m.email LIKE :search')
            ->orWhere('m.telNum LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    public function findAllBySearch($search){
        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.id', 'desc')
            ->where('m.firstName LIKE :search')
            ->orWhere('m.lastName LIKE :search')
            ->orWhere('m.email LIKE :search')
            ->orWhere('m.telNum LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
