<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Congestion;
use AdminBundle\Services\CopyGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Congestion controller.
 *
 * @Route("admin/congestion")
 */
class CongestionController extends Controller
{
    /**
     * Lists all congestion entities.
     *
     * @Route("/", name="admin_congestion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $congestions = $em->getRepository('CommonBundle:Congestion')->findAll();

        return $this->render('AdminBundle:congestion:index.html.twig', array(
            'congestions' => $congestions,
        ));
    }

    /**
     * Creates a new congestion entity.
     *
     * @Route("/new", name="admin_congestion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $congestion = new Congestion();
        $form = $this->createForm('CommonBundle\Form\CongestionType', $congestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($congestion);
            $em->flush($congestion);

            return $this->redirectToRoute('admin_congestion_show', array('id' => $congestion->getId()));
        }

        return $this->render('congestion/new.html.twig', array(
            'congestion' => $congestion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a congestion entity.
     *
     * @Route("/{id}", name="admin_congestion_show")
     * @Method("GET")
     */
    public function showAction(Congestion $congestion)
    {
        $deleteForm = $this->createDeleteForm($congestion);

        return $this->render('congestion/show.html.twig', array(
            'congestion' => $congestion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing congestion entity.
     *
     * @Route("/{id}/edit", name="admin_congestion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Congestion $congestion)
    {
        $deleteForm = $this->createDeleteForm($congestion);
        $editForm = $this->createForm('CommonBundle\Form\CongestionType', $congestion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_congestion_edit', array('id' => $congestion->getId()));
        }

        return $this->render('congestion/edit.html.twig', array(
            'congestion' => $congestion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a congestion entity.
     *
     * @Route("/{id}", name="admin_congestion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Congestion $congestion)
    {
        $form = $this->createDeleteForm($congestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($congestion);
            $em->flush($congestion);
        }

        return $this->redirectToRoute('admin_congestion_index');
    }

    /**
     * Creates a form to delete a congestion entity.
     *
     * @param Congestion $congestion The congestion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Congestion $congestion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_congestion_delete', array('id' => $congestion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
