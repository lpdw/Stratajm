<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\PaymentMethod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Paymentmethod controller.
 *
 * @Route("admin/paymentmethod")
 */
class PaymentMethodController extends Controller
{
    /**
     * Lists all paymentMethod entities.
     *
     * @Route("/", name="admin_paymentmethod_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paymentMethods = $em->getRepository('CommonBundle:PaymentMethod')->findAll();

        return $this->render('AdminBundle:paymentmethod:index.html.twig', array(
            'paymentMethods' => $paymentMethods,
        ));
    }

    /**
     * Creates a new paymentMethod entity.
     *
     * @Route("/new", name="admin_paymentmethod_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $paymentMethod = new Paymentmethod();
        $form = $this->createForm('CommonBundle\Form\PaymentMethodType', $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paymentMethod);
            $em->flush($paymentMethod);

            return $this->redirectToRoute('admin_paymentmethod_show', array('id' => $paymentMethod->getId()));
        }

        return $this->render('AdminBundle:paymentmethod:new.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a paymentMethod entity.
     *
     * @Route("/{id}", name="admin_paymentmethod_show")
     * @Method("GET")
     */
    public function showAction(PaymentMethod $paymentMethod)
    {
        $deleteForm = $this->createDeleteForm($paymentMethod);

        return $this->render('AdminBundle:paymentmethod:show.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing paymentMethod entity.
     *
     * @Route("/{id}/edit", name="admin_paymentmethod_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PaymentMethod $paymentMethod)
    {
        $deleteForm = $this->createDeleteForm($paymentMethod);
        $editForm = $this->createForm('CommonBundle\Form\PaymentMethodType', $paymentMethod);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_paymentmethod_edit', array('id' => $paymentMethod->getId()));
        }

        return $this->render('AdminBundle:paymentmethod:edit.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a paymentMethod entity.
     *
     * @Route("/{id}", name="admin_paymentmethod_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PaymentMethod $paymentMethod)
    {
        $form = $this->createDeleteForm($paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paymentMethod);
            $em->flush($paymentMethod);
        }

        return $this->redirectToRoute('admin_paymentmethod_index');
    }

    /**
     * Creates a form to delete a paymentMethod entity.
     *
     * @param PaymentMethod $paymentMethod The paymentMethod entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PaymentMethod $paymentMethod)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_paymentmethod_delete', array('id' => $paymentMethod->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
