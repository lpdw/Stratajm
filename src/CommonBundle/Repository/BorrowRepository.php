<?php

namespace CommonBundle\Repository;

/**
 * BorrowRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BorrowRepository extends \Doctrine\ORM\EntityRepository
{

  public function findCurrentBorrowings($currentDate) {
    return $this
      ->createQueryBuilder('b')
      ->select(array('b'))
      ->where("b.endDate < :currentDate")
      ->setParameter('currentDate', $currentDate)
      ->getQuery()
      ->getResult();
  }

  public function findEndedBorrowings($currentDate) {
    return $this
      ->createQueryBuilder('b')
      ->select(array('b'))
      ->where("b.endDate >= :currentDate")
      ->setParameter('currentDate', $currentDate)
      ->getQuery()
      ->getResult();
  }

  public function findAllByArg($order = 'desc', $offset = 0, $limit = 10){
      $result = $this->createQueryBuilder('m')
          ->select('m')
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

  public function findBySearch($search, $order = 'desc', $offset = 0, $limit = 10){

      $result = $this->createQueryBuilder('m')
          ->select('m')
          ->orderBy('m.id', $order)
          ->where('m.beginDate LIKE :search')
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
          ->where('m.beginDate LIKE :search')
          ->setParameter('search', '%'.$search.'%')
          ->getQuery()
          ->getSingleScalarResult();

      return $result;
  }

}
