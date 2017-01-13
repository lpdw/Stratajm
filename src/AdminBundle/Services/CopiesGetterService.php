<?php

namespace AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use CommonBundle\Entity\Copy;

class CopiesGetterService {

  /**
  * @var EntityManager
  */
  private $doctrine;

  /**
  * @param EntityManager $doctrine
  * @param int $gameId
  */
  public function __construct($doctrine) {
    $this->doctrine = $doctrine;
  }

  /**
  * @param int $gameId Idenfifiant du jeu
  */
  public function getCopies($idGame) {
    $em = $this->doctrine->getManager();

    $copies = $em->getRepository('CommonBundle:Copy')->findByGame($idGame);

    return $copies;

  }

}

 ?>
