<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Localisation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Localisation controller.
 *
 * @Route("admin/localisation")
 */
class LocalisationController extends Controller
{
    /**
     * Lists all localisation entities.
     *
     * @Route("/", name="admin_localisation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $localisations = $em->getRepository('CommonBundle:Localisation')->findAll();

        return $this->render('AdminBundle:localisation:index.html.twig', array(
            'localisations' => $localisations,
        ));
    }

    /**
     * Creates a new localisation entity.
     *
     * @Route("/new", name="admin_localisation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $localisation = new Localisation();
        $form = $this->createForm('CommonBundle\Form\LocalisationType', $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($localisation);
            $em->flush($localisation);

            return $this->redirectToRoute('admin_localisation_show', array('id' => $localisation->getId()));
        }

        return $this->render('AdminBundle:localisation:new.html.twig', array(
            'localisation' => $localisation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a localisation entity.
     *
     * @Route("/{id}", name="admin_localisation_show")
     * @Method("GET")
     */
    public function showAction(Localisation $localisation)
    {
        $deleteForm = $this->createDeleteForm($localisation);

        return $this->render('AdminBundle:localisation:show.html.twig', array(
            'localisation' => $localisation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing localisation entity.
     *
     * @Route("/{id}/edit", name="admin_localisation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Localisation $localisation)
    {
        $deleteForm = $this->createDeleteForm($localisation);
        $editForm = $this->createForm('CommonBundle\Form\LocalisationType', $localisation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_localisation_edit', array('id' => $localisation->getId()));
        }

        return $this->render('AdminBundle:localisation:edit.html.twig', array(
            'localisation' => $localisation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a localisation entity.
     *
     * @Route("/{id}", name="admin_localisation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Localisation $localisation)
    {
        $form = $this->createDeleteForm($localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($localisation);
            $em->flush($localisation);
        }

        return $this->redirectToRoute('admin_localisation_index');
    }

    /**
     * Creates a form to delete a localisation entity.
     *
     * @param Localisation $localisation The localisation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Localisation $localisation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_localisation_delete', array('id' => $localisation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
