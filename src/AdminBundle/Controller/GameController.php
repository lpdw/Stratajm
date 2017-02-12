<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Game;
use AdminBundle\Services\CopyGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Game controller.
 *
 * @Route("admin/game")
 */
class GameController extends Controller
{
    /**
     * Lists all game entities.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository('CommonBundle:Game')->findAll();

        return $this->render('AdminBundle:game:index.html.twig', array(
            'games' => $games,
        ));
    }

    /**
     * Creates a new game entity.
     *
     * @Route("/new", name="admin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm('CommonBundle\Form\GameType', $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($game->getImage())
            {
                $file = $game->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $game->setImage($fileName);
            }
            if($game->getBoardImage())
            {
                $file = $game->getBoardImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $game->setBoardImage($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            if($game->getTraditional())
                $game->setReleaseDate(new \DateTime('1900-01-01'));

            // création du jeu
            $em->persist($game);
            $em->flush($game);

            // création du nombre d'exemplaires saisis
            $nbcopies = $form['nbcopies']->getData();
            $copygenerator = $this->get('app.copygenerator');
//            dump($copygenerator);die;
            $copygenerator->createGameCopies($game->getId(), $nbcopies);

            return $this->redirectToRoute('admin_show', array('id' => $game->getId()));
        }

        return $this->render('AdminBundle:game:new.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a game entity.
     *
     * @Route("/{id}", name="admin_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Game $game)
    {
        $deleteForm = $this->createDeleteForm($game);

        $em = $this->getDoctrine()->getManager();
        $copies = $em->getRepository('CommonBundle:Copy')->findBy(
          array('game' => $game)
        );

        //$nbcopies = $em->getRepository('CommonBundle:Copy')->countCopiesByGame($game);
        switch($game->getDuration()) {
          case 0:
            $duration="Courte (<= 30min)";
          break;

          case 1:
            $duration="Moyenne (30-45min)";
          break;

          case 2:
            $duration="Longue (~1h)";
          break;

          case 3:
            $duration="Très longue (+1h)";
          break;
        }

        return $this->render('AdminBundle:game:show.html.twig', array(
            'game' => $game,
            'duration' => $duration,
            'delete_form' => $deleteForm->createView(),
            'copies' => $copies,
        ));
    }

    /**
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/edit", name="admin_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Game $game)
    {
        $oldImageName = $game->getImage();
        $oldBoardImageName = $game->getBoardImage();

        if($game->getImage()) {
            //Transform the string filename in a file object for the FileType field
            $game->setImage(
                new File($this->getParameter('images_directory').'/'.$game->getImage())
            );
        }
        if($game->getBoardImage()) {
            //Transform the string filename in a file object for the FileType field
            $game->setBoardImage(
                new File($this->getParameter('images_directory').'/'.$game->getBoardImage())
            );
        }

        $deleteForm = $this->createDeleteForm($game);
        $editForm = $this->createForm('CommonBundle\Form\GameType', $game);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //Check if a new image has been uploaded
            if($game->getImage()) {
                if($oldImageName)
                    unlink($this->getParameter('images_directory').'/'.$oldImageName);

                $file = $game->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $game->setImage($fileName);
            }
            //else we keep the old image
            else {
                $game->setImage($oldImageName);
            }

            if($game->getBoardImage()) {
                if($oldBoardImageName)
                    unlink($this->getParameter('images_directory').'/'.$oldBoardImageName);

                $file = $game->getBoardImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $game->setBoardImage($fileName);
            }
            //else we keep the old image
            else {
                $game->setBoardImage($oldBoardImageName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_show', array('id' => $game->getId()));
        }

        return $this->render('AdminBundle:game:edit.html.twig', array(
            'game' => $game,
            'oldImageName' => $oldImageName,
            'oldBoardImageName' => $oldBoardImageName,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Lists all games entities.
     *
     * @Route("/json/games", name="games_resp" )
     *
     * @Method({"GET", "POST"})
     */
    public function dataAction(Request $request)
    {
        $search = $request->query->get('search');
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');
        $order = $request->query->get('order');

        $em = $this->getDoctrine()->getManager();

        //cas ou le nombre de caractère de la recherche est inférieur à 2
        if( strlen($search)<2) {

            //on recupère tous les jeux
            $games = $em->getRepository('CommonBundle:Game')->findAllByArg($order, $offset, $limit);

            $nbRows = $em->getRepository('CommonBundle:Game')->countAll();
        } else {
            //cas d'une recherche
            $games = $em->getRepository('CommonBundle:Game')->findBySearch($search,$order,$offset,$limit);

            $nbRows = $em->getRepository('CommonBundle:Game')->countAllBySearch($search);
        }
        $rows =[];

        //on commence le formatage du tableau
        foreach ($games as $game) {
            $line['id'] = $game->getId();
            $line['image'] = $game->getImage();
            $line['name'] = $game->getName();
            $line['age'] = $game->getAgeMin();
            $line['duration'] = $game->getDuration();
            $line['rules'] = $game->getRules();
            $line['releaseDate'] = date_format($game->getReleaseDate(), "Y");

            $rows[] = $line;
        }
        $result['total'] = $nbRows;
        $result['rows'] = $rows;

        return new JsonResponse($result);
    }

    /**
     * Deletes a game entity.
     *
     * @Route("/{id}", name="admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Game $game)
    {
        $form = $this->createDeleteForm($game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($game->getImage())
            {
                unlink($this->getParameter('images_directory').'/'.$game->getImage());
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush($game);
        }

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Creates a form to delete a game entity.
     *
     * @param Game $game The game entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Game $game)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $game->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
