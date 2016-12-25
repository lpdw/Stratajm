<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Membership controller.
 *
 * @Route("admin/membership")
 */
class MembershipController extends Controller
{
    /**
     * Lists all membership entities.
     *
     * @Route("/", name="admin_membership_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $memberships = $em->getRepository('CommonBundle:Membership')->findAll();

        return $this->render('AdminBundle:membership:index.html.twig', array(
            'memberships' => $memberships,
        ));
    }

    /**
     * Creates a new membership entity.
     *
     * @Route("/new", name="admin_membership_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membership = new Membership();
        $form = $this->createForm('CommonBundle\Form\MembershipType', $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membership);
            $em->flush($membership);

            return $this->redirectToRoute('admin_membership_show', array('id' => $membership->getId()));
        }

        return $this->render('AdminBundle:membership:new.html.twig', array(
            'membership' => $membership,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a membership entity.
     *
     * @Route("/{id}", name="admin_membership_show")
     * @Method("GET")
     */
    public function showAction(Membership $membership)
    {
        $deleteForm = $this->createDeleteForm($membership);

        return $this->render('AdminBundle:membership:show.html.twig', array(
            'membership' => $membership,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing membership entity.
     *
     * @Route("/{id}/edit", name="admin_membership_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Membership $membership)
    {
        $deleteForm = $this->createDeleteForm($membership);
        $editForm = $this->createForm('CommonBundle\Form\MembershipType', $membership);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_membership_edit', array('id' => $membership->getId()));
        }

        return $this->render('AdminBundle:membership:edit.html.twig', array(
            'membership' => $membership,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a membership entity.
     *
     * @Route("/{id}", name="admin_membership_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Membership $membership)
    {
        $form = $this->createDeleteForm($membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membership);
            $em->flush($membership);
        }

        return $this->redirectToRoute('admin_membership_index');
    }

    /**
     * Creates a form to delete a membership entity.
     *
     * @param Membership $membership The membership entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Membership $membership)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_membership_delete', array('id' => $membership->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
