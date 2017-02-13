<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Borrow;
use CommonBundle\Entity\Copy;
use CommonBundle\Entity\Game;
use CommonBundle\Form\BorrowType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
/**
 * Borrow controller.
 *
 * @Route("admin/borrow")
 */
class BorrowController extends Controller
{
    /**
     * Lists all borrow entities.
     *
     * @Route("/", name="admin_borrow_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $borrowings = $em->getRepository('CommonBundle:Borrow')->findAll();


        /* TODO : comment gérer la fin d'un emprunt ?
        $date = new \DateTime();
        $currentDate = $date->format('Y-m-d H:i:s');

        $currentBorrowings = $em->getRepository('CommonBundle:Borrow')->findCurrentBorrowings($currentDate);
        $endedBorrowings = $em->getRepository('CommonBundle:Borrow')->findEndedBorrowings($currentDate);
        */

        return $this->render('AdminBundle:borrow:index.html.twig', array(
            'borrowings' => $borrowings,
            //'currentBorrowings' => $currentBorrowings,
            //'endedBorrowings' => $endedBorrowings
        ));
    }

    /**
     * Creates a new borrow entity.
     *
     * @Route("/new", name="admin_borrow_new",options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $borrow = new Borrow();
        $form = $this->createForm("CommonBundle\Form\BorrowType",$borrow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $copy = $em->getRepository('CommonBundle:Copy')->findById($request->request->get('borrow')['copy']);
            $borrow->setCopy($copy[0]);

            $em->persist($borrow);
            $em->flush($borrow);
            return $this->redirectToRoute('admin_borrow_show', array('id' => $borrow->getId()));
        }

        // On récupère le jeu  envoyé en ajax
        if($request->isXmlHttpRequest()){
          $game=$request->request->get('game');
          // On récupère la liste des copies pour le jeu selectionné
          $copies = $em->getRepository('CommonBundle:Copy')->getCopiesByGame($game);
          return new JsonResponse($copies);

        }

        return $this->render('AdminBundle:borrow:new.html.twig', array(
            'borrow' => $borrow,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a borrow entity.
     *
     * @Route("/{id}", name="admin_borrow_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Borrow $borrow)
    {
        $deleteForm = $this->createDeleteForm($borrow);


        return $this->render('AdminBundle:borrow:show.html.twig', array(
            'borrow' => $borrow,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing borrow entity.
     *
     * @Route("/{id}/edit", name="admin_borrow_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Borrow $borrow)
    {
        $deleteForm = $this->createDeleteForm($borrow);
        $editForm = $this->createForm('CommonBundle\Form\BorrowType', $borrow);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_borrow_edit', array('id' => $borrow->getId()));
        }

        return $this->render('AdminBundle:borrow:edit.html.twig', array(
            'borrow' => $borrow,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Lists all borrows entities.
     *
     * @Route("/json/borrows", name="borrows_resp" )
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

            //on recupère tous les emprunts
            $borrows = $em->getRepository('CommonBundle:Borrow')->findAllByArg($order, $offset, $limit);

            $nbRows = $em->getRepository('CommonBundle:Borrow')->countAll();
        } else {
            //cas d'une recherche
            $borrows = $em->getRepository('CommonBundle:Borrow')->findBySearch($search,$order,$offset,$limit);

            $nbRows = $em->getRepository('CommonBundle:Borrow')->countAllBySearch($search);
        }
        $rows =[];

        //on commence le formatage du tableau
        foreach ($borrows as $borrow) {

            $copy = $borrow->getCopy();
            $member = $borrow->getMember();
            $line['id'] = $borrow->getId();
            $line['game'] = $copy->getGame()->getName();
            $line['reference'] = $copy->getReference();
            $line['borrower'] = [$member->getId(), $member->getFirstName(), $member->getLastName()];
            $line['borrowdate'] = date_format($borrow->getBeginDate(), "m/d/Y");

            $rows[] = $line;

        }
        $result['total'] = $nbRows;
        $result['rows'] = $rows;

        return new JsonResponse($result);
    }

    /**
     * Deletes a borrow entity.
     *
     * @Route("/{id}", name="admin_borrow_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Borrow $borrow)
    {
        $form = $this->createDeleteForm($borrow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($borrow);
            $em->flush($borrow);
        }

        return $this->redirectToRoute('admin_borrow_index');
    }

    /**
     * Creates a form to delete a borrow entity.
     *
     * @param Borrow $borrow The borrow entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Borrow $borrow)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_borrow_delete', array('id' => $borrow->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
