<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $searchValue=$request->request->get('input');

        //Gestion de l'autocompletion du champs de recherche par
        if($searchValue!=null){
          $gamesFound = $em->getRepository('CommonBundle:Game')->searchGame($searchValue);
          return new JsonResponse(array('games'=>json_encode($gamesFound)));

        }

        if ($gameSearchForm->isSubmitted() && $gameSearchForm->isValid()) {
          $name=$gameSearchForm['searchGame']->getData();
          $gameFound = $em->getRepository('CommonBundle:Game')->searchGameByName($name);
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


}
