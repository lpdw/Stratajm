<?php

namespace CommonBundle\Repository;

/**
 * PublisherRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublisherRepository extends \Doctrine\ORM\EntityRepository
{
  public function getAllIds()
  {
      return $this
      ->createQueryBuilder('p')
      ->select('p.id')
      ->getQuery()
      ->getArrayResult();
  }
}
