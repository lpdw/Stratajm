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
    public function searchGame($searchValue)
    {
        return $this
    ->createQueryBuilder('g')
    ->select(array('g'))
    ->where("g.name LIKE :searchValue")
    ->setParameter('searchValue', "%".$searchValue."%")
    ->getQuery()
    ->getArrayResult();
    }

    public function searchGameByName($name)
    {
        return $this
      ->createQueryBuilder('g')
      ->where("g.name LIKE :name")
      ->setParameter('name', "%".$name."%")
      ->getQuery()
      ->getArrayResult();
    }


    public function getAllPublishersById()
    {
        return $this
        ->createQueryBuilder('g')
        ->select('g.id')
        ->leftJoin('g.publisher', 'p', 'WITH', 'g.publisher = p.id')
        ->getQuery()
        ->getArrayResult();
    }

    public function sortBy($publishersID, $orderby, $ageMin, $ageMax, $duration)
    {
        if ($orderby=="publication_asc") {
            $sort='g.releaseDate';
            $order='ASC';
        }
        if ($orderby=="publication_desc") {
            $sort='g.releaseDate';
            $order='DESC';
        }
        if ($orderby=="ajout_asc") {
            $sort='g.id';
            $order='ASC';
        }
        if ($orderby=="ajout_desc") {
            $sort='g.id';
            $order='DESC';
        }
        if($duration==null){
          $requestDuree="g.duration < :duration";
          $duration=4;
        }else{
          $requestDuree="g.duration = :duration";
        }


        $request = $this->createQueryBuilder('g')
        ->select(array('g', 'p'))
        ->leftJoin('g.publisher', 'p', 'WITH', 'g.publisher = p.id')
          ->where("g.publisher IN (:publisher_id)")
          ->andWhere("g.ageMax >= :ageMax")
          ->andWhere("g.ageMin <= :ageMin")
          ->andwhere($requestDuree)
          ->setParameter('publisher_id', $publishersID)
          ->setParameter('ageMin', $ageMin)
          ->setParameter('ageMax', $ageMax)
          ->setParameter('duration', $duration)

          ->orderBy($sort, $order)
          ->getQuery()
          ->getArrayResult();



        //$request->getQuery()
        //->getArrayResult();
        return $request;
    }
}
