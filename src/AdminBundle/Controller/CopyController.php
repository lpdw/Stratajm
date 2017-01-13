<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Copy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Copy controller.
 *
 * @Route("admin/copy")
 */
class CopyController extends Controller
{
    /**
     * Lists all copy entities.
     *
     * @Route("/", name="admin_copy_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $copies = $em->getRepository('CommonBundle:Copy')->findAll();

        return $this->render('AdminBundle:copy:index.html.twig', array(
            'copies' => $copies,
        ));
    }

    /**
     * Creates a new copy entity.
     *
     * @Route("/new", name="admin_copy_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $copy = new Copy();
        $form = $this->createForm('CommonBundle\Form\CopyType', $copy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($copy);
            $em->flush($copy);

            return $this->redirectToRoute('admin_copy_show', array('id' => $copy->getId()));
        }

        return $this->render('AdminBundle:copy:new.html.twig', array(
            'copy' => $copy,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a copy entity.
     *
     * @Route("/{id}", name="admin_copy_show")
     * @Method("GET")
     */
    public function showAction(Copy $copy)
    {
        $deleteForm = $this->createDeleteForm($copy);

        return $this->render('AdminBundle:copy:show.html.twig', array(
            'copy' => $copy,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing copy entity.
     *
     * @Route("/{id}/edit", name="admin_copy_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Copy $copy)
    {
        $deleteForm = $this->createDeleteForm($copy);
        $editForm = $this->createForm('CommonBundle\Form\CopyType', $copy);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_copy_edit', array('id' => $copy->getId()));
        }

        return $this->render('AdminBundle:copy:edit.html.twig', array(
            'copy' => $copy,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a copy entity.
     *
     * @Route("/{id}", name="admin_copy_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Copy $copy)
    {
        $form = $this->createDeleteForm($copy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($copy);
            $em->flush($copy);
        }

        return $this->redirectToRoute('admin_copy_index');
    }

    /**
     * Creates a form to delete a copy entity.
     *
     * @param Copy $copy The copy entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Copy $copy)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_copy_delete', array('id' => $copy->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
