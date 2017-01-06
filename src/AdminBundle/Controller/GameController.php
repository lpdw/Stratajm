<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Game;
use AdminBundle\Services\CopyGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Game controller.
 *
 * @Route("admin")
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

            $em = $this->getDoctrine()->getManager();

            // création du jeu
            $em->persist($game);
            $em->flush($game);

            // création du nombre d'exemplaires saisis
            $nbcopies = $form['nbcopies']->getData();
            $copygenerator = $this->get('app.copygenerator');
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
     * @Route("/{id}", name="admin_show")
     * @Method("GET")
     */
    public function showAction(Game $game)
    {
        $deleteForm = $this->createDeleteForm($game);

        $em = $this->getDoctrine()->getManager();
        $nbcopies = $em->getRepository('CommonBundle:Copy')->countCopiesByGame($game->getId());

        return $this->render('AdminBundle:game:show.html.twig', array(
            'game' => $game,
            'delete_form' => $deleteForm->createView(),
            'nbcopies' => $nbcopies,
        ));
    }

    /**
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/edit", name="admin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Game $game)
    {
        $oldImageName = $game->getImage();

        if($game->getImage()) {
            //Transform the string filename in a file object for the FileType field
            $game->setImage(
                new File($this->getParameter('images_directory').'/'.$game->getImage())
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

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_show', array('id' => $game->getId()));
        }

        return $this->render('AdminBundle:game:edit.html.twig', array(
            'game' => $game,
            'oldImageName' => $oldImageName,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
