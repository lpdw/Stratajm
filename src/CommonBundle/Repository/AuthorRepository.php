<?php

namespace CommonBundle\Repository;

/**
 * AuthorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AuthorRepository extends \Doctrine\ORM\EntityRepository
{
  public function getAllIds()
  {
      return $this
      ->createQueryBuilder('a')
      ->select('a.id')
      ->getQuery()
      ->getArrayResult();
  }
}
