<?php

namespace CommonBundle\Repository;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends \Doctrine\ORM\EntityRepository
{

  public function searchGame($searchValue){
    return $this
    ->createQueryBuilder('g')
    ->select(array('g'))
    ->where("g.name LIKE :searchValue")
    ->setParameter('searchValue',"%".$searchValue."%")
    ->getQuery()
    ->getArrayResult();  }

    public function searchGameByName($name){
      return $this
      ->createQueryBuilder('g')
      ->where("g.name LIKE :name")
      ->setParameter('name',"%".$name."%")
      ->getQuery()
      ->getArrayResult();  }


      /*public function searchGameByPublisher(pybk){
        return $this
        ->createQueryBuilder('g')
        ->where("g.name LIKE :name")
        ->setParameter('name',"%".$name."%")
        ->getQuery()
        ->getSingleResult();  }

*/
}
