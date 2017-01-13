<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Borrow;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/new", name="admin_borrow_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $borrow = new Borrow();
        $form = $this->createForm('CommonBundle\Form\BorrowType', $borrow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($borrow);
            $em->flush($borrow);

            return $this->redirectToRoute('admin_borrow_show', array('id' => $borrow->getId()));
        }

        return $this->render('AdminBundle:borrow:new.html.twig', array(
            'borrow' => $borrow,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a borrow entity.
     *
     * @Route("/{id}", name="admin_borrow_show")
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
     * @Route("/{id}/edit", name="admin_borrow_edit")
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
