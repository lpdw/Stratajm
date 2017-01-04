<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CommonBundle\Form\GameSearchType;
use CommonBundle\Form\GameSortType;

use CommonBundle\Repository;
use CommonBundle\Entity\Game;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CommonBundle:Default:index.html.twig');
    }

    /**
     * @Route("/games", name="display_games",options={"expose"=true})
     */
    public function displayGamesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $gameSortForm = $this->createForm(GameSortType::class);
        $gameSortForm->handleRequest($request);

        $gameSearchForm = $this->createForm(GameSearchType::class);
        $gameSearchForm->handleRequest($request);

        //Gestion de l'autocompletion du champs de recherche par nom
        $searchValue=$request->request->get('gameName');
        if($searchValue!=null){
          // On récupère le jeu correspondant
          $gamesFound = $em->getRepository('CommonBundle:Game')->searchGame($searchValue);
          return new JsonResponse(array('games'=>json_encode($gamesFound)));

        }

        // Gestion du tri des jeux par AJAX
        // on récupère toutes les données
        if($request->isXmlHttpRequest()){
          $publishersID=$request->request->get('publishers');
          $orderby=$request->request->get('orderby');
          $ageMin=$request->request->get('ageMin');
          $ageMax=$request->request->get('ageMax');
          $duration=$request->request->get('duration');
          if($publishersID==null){
            $publishersID=$em->getRepository('CommonBundle:Game')->getAllPublishersById();
          }
          if($ageMin==null && $ageMax==null){
            $ageMin=200;
            $ageMax=0;
          }
          elseif($ageMax==null && $ageMin!=null){
            $ageMax=$ageMin;
          }
          elseif($ageMax!=null && $ageMin==null){
            $ageMin=$ageMax;
          }




          $gamesSorted = $em->getRepository('CommonBundle:Game')->sortBy($publishersID,$orderby,$ageMin,$ageMax,$duration);
          return new JsonResponse(array('games'=>json_encode($gamesSorted)));

        }



        if ($gameSearchForm->isSubmitted() && $gameSearchForm->isValid()) {
          $name=$gameSearchForm['searchGame']->getData();
          $gameFound = $em->getRepository('CommonBundle:Game')->searchGameByName($name);
          
          // Une fois le formulaire valide et le resultat trouvé, on l'initialise
          unset($gameSearchForm);
          $gameSearchForm = $this->createForm(GameSearchType::class);

          return $this->render('CommonBundle:Default:displayGames.html.twig', array(
              'games' => $gameFound,"searchForm"=>$gameSearchForm->createView(),"sortForm"=>$gameSortForm->createView()
          ));
        }

          // Par défaut on affiche tous les jeux
          $games = $em->getRepository('CommonBundle:Game')->findAll();
          return $this->render('CommonBundle:Default:displayGames.html.twig', array(
              'games' => $games,"searchForm"=>$gameSearchForm->createView(),"sortForm"=>$gameSortForm->createView()
          ));

    }

    /**
     * @Route("/singlegame/{id}", name="single_game")
     * @Method("GET")
     */
    public function singleGameAction(Game $game)
    {
      return $this->render('CommonBundle:Default:singleGame.html.twig', array('game' => $game));
    }


}
