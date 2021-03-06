<?php

namespace CommonBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
      ->getQuery();
    }

    public function getAllGames()
    {
      return $this->createQueryBuilder('g')
      ->getQuery();
    }

    public function countAllGames(){
      return $this->createQueryBuilder('g')
            ->select('COUNT(g)')
            ->getQuery()
            ->getResult();
    }





    public function sortBy($publishersID, $orderby, $ageMin,$ageMax,$duration,$types,$themes,$authors,$country,$congestion,$players)
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
        ->leftJoin('g.publishers', 'pu')
        ->leftJoin('g.types', 't')
        ->leftJoin('g.themes', 'th')
        ->leftJoin('g.authors', 'a')
        ->leftJoin('g.country', 'c')
        ->leftJoin('g.congestion', 'con')
        ->leftJoin('g.nbPlayers', 'p')
        ->where("pu.id IN (:publisher_id)")
        ->andWhere('t.id IN (:types_id)')
        ->andWhere('th.id IN (:themes_id)')
        ->andWhere('a.id IN (:authors)')
        ->andWhere('c.id IN (:countries)')
        ->andWhere('con.id IN (:congestions)')
        ->andWhere('p.id IN (:players)')
        ->andWhere("g.ageMin >= :ageMin")
        ->andWhere("g.ageMin <= :ageMax")
        ->andwhere($requestDuree)
        ->setParameter('publisher_id', $publishersID)
        ->setParameter('themes_id',$themes)
        ->setParameter('authors',$authors)
        ->setParameter('countries',$country)
        ->setParameter('congestions',$congestion)
        ->setParameter('players',$players)
        ->setParameter('types_id', $types)
        ->setParameter('ageMin', $ageMin)
        ->setParameter('ageMax', $ageMax)
        ->setParameter('duration', $duration)
        ->orderBy($sort, $order)
        ->getQuery();
        return $request;
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
            ->where('m.name LIKE :search')
            ->orWhere('m.duration LIKE :search')
            ->orWhere('m.rules LIKE :search')
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
            ->where('m.name LIKE :search')
            ->orWhere('m.duration LIKE :search')
            ->orWhere('m.rules LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

}
