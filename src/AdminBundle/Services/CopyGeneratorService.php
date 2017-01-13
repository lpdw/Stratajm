<?php

namespace AdminBundle\Services;
use Doctrine\ORM\EntityManager;
use CommonBundle\Entity\Copy;

class CopyGeneratorService {


  /**
  * @var EntityManager
  */
  private $doctrine;


  /**
  * @var int
  */
  private $gameId;

  /**
  * @var int
  */
  private $nbcopies;


  /**
  * @param EntityManager $doctrine
  * @param int $gameId
  * @param int $nbcopies
  */
  public function __construct($doctrine, $gameId, $nbcopies) {
    $this->doctrine = $doctrine;
    $this->gameId = $gameId;
    $this->nbcopies = $nbcopies;
  }


  /**
  * Créé le nombre d'exemplaires associés au jeu
  * @param int $gameId Identifiant du jeu
  * @return bool
  */
  public function createGameCopies($gameId, $nbcopies) {

    $em = $this->doctrine->getManager();

    $game = $em->getRepository('CommonBundle:Game')->findOneById($gameId);
    $status = $em->getRepository('CommonBundle:Status')->findOneByName('Neuf');
    $localisation = $em->getRepository('CommonBundle:Localisation')->findOneByName('Stock');

    for($i = 0 ; $i<$nbcopies ; $i++) {

      // TO REMOVE : Generating a random test string to fill the reference field
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);$randomString = '';
      for ($j = 0; $j < 10; $j++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      // TO REMOVE

      $copy = new Copy();
      $copy->setGame($game);
      $copy->setReference("CopyGeneratorService.createGameCopies:".$randomString);
      $copy->setStatus($status);
      $copy->setLocalisation($localisation);

      $em->persist($copy);
      $em->flush();

    }


    return true;

  }

}

?>
