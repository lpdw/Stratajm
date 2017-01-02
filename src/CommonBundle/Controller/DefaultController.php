<?php

namespace CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/games", name="display_games")
     */
    public function displayGamesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository('CommonBundle:Game')->findAll();

        return $this->render('CommonBundle:Default:displayGames.html.twig', array(
            'games' => $games,
        ));
    }


}
