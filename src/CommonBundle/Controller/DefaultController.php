<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CommonBundle\Form\GameSearchType;
use CommonBundle\Form\GameSortType;

use CommonBundle\Repository\GameRepository;
use CommonBundle\Repository\PublisherRepository;
use CommonBundle\Repository\PlayersRepository;
use CommonBundle\Repository\CongestionRepository;

use CommonBundle\Repository\AuthorRepository;

use CommonBundle\Entity\Game;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/games/{page}", defaults={"page"=1}, name="display_games",options={"expose"=true})
     */
    public function displayGamesAction(Request $request,$page)
    {
        $em = $this->getDoctrine()->getManager();

        // Initialisation des deux formulaire : recherche par nom et tri
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

        // Gestion du tri par nom
        if ($gameSearchForm->isSubmitted() && $gameSearchForm->isValid()) {
          // On récupère le nom saisi
          $name=$gameSearchForm['searchGame']->getData();


          $gameFound = $em->getRepository('CommonBundle:Game')->searchGameByName($name);

          // Une fois le formulaire valide et le resultat trouvé, on vide le formulaire et on affiche le résultat
          unset($gameSearchForm);
          $gameSearchForm = $this->createForm(GameSearchType::class);
          $paginator  = $this->get('knp_paginator');
          $pagination = $paginator->paginate(
             $gameFound, /* query NOT result */
             $request->query->get('page',$page),
             10/*limit per page*/
          );
          return $this->render('CommonBundle:Default:displayGames.html.twig', array(
              'pagination'=>$pagination,"searchForm"=>$gameSearchForm->createView(),"sortForm"=>$gameSortForm->createView()
          ));
        }

        // Gestion du tri des jeux par AJAX
        // on récupère toutes les données
        if($request->isXmlHttpRequest()){
          $publishersID=$request->request->get('publishers');
          $orderby=$request->request->get('orderby');
          $ageMin=$request->request->get('ageMin');
          $ageMax=$request->request->get('ageMax');
          $duration=$request->request->get('duration');
          $types=$request->request->get('types');
          $themes=$request->request->get('themes');
          $authors=$request->request->get('authors');
          $country=$request->request->get('country');
          $congestion=$request->request->get('congestion');
          $players=$request->request->get('players');


          if($publishersID==null){
            // Si aucun editeur de jeu n'est selectionné, on les récupère tous
            $publishersID=$em->getRepository('CommonBundle:Publisher')->findAll();
          }
          if($authors==null){
            $authors=$em->getRepository('CommonBundle:Author')->findAll();
          }
          if($country==null){
            $country=$em->getRepository('CommonBundle:Country')->findAll();
          }
          if($congestion==null){
            $congestion=$em->getRepository('CommonBundle:Congestion')->findAll();
          }
          if($players==null){
            $players=$em->getRepository('CommonBundle:Players')->findAll();
          }
          if($types==null){
            // Si aucun type de jeu n'est selectionné, on les récupère tous
            $types = $em->getRepository('CommonBundle:Type')->findAll();
          }
          if($themes==null){
            // Si aucun theme de jeu n'est selectionné, on les récupère tous
            $themes = $em->getRepository('CommonBundle:Theme')->findAll();
          }


          $gamesSorted = $em->getRepository('CommonBundle:Game')->sortBy($publishersID,$orderby,$ageMin,$ageMax,$duration,$types,$themes,$authors,$country,$congestion,$players);
          $paginator  = $this->get('knp_paginator');
          $pagination = $paginator->paginate(
             $gamesSorted, /* query NOT result */
             $request->query->get('page', $page)/*page number*/,
             10/*limit per page*/
         );
          $updatedVue=$this->renderView('CommonBundle:Default:listGames.html.twig', array(
              'pagination'=>$pagination,
          ));
          return new Response(json_encode($updatedVue));

        }

        // Par défaut on affiche tous les jeux
        $games = $em->getRepository('CommonBundle:Game')->getAllGames();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
             $games, /* query */
             $request->query->get('page', $page),
             10/*limite par page*/
        );
        return $this->render('CommonBundle:Default:displayGames.html.twig', array(
            'pagination'=>$pagination,"searchForm"=>$gameSearchForm->createView(),"sortForm"=>$gameSortForm->createView()
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
