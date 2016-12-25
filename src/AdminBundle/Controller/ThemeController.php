<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Theme controller.
 *
 * @Route("admin/theme")
 */
class ThemeController extends Controller
{
    /**
     * Lists all theme entities.
     *
     * @Route("/", name="admin_theme_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $themes = $em->getRepository('CommonBundle:Theme')->findAll();

        return $this->render('AdminBundle:theme:index.html.twig', array(
            'themes' => $themes,
        ));
    }

    /**
     * Creates a new theme entity.
     *
     * @Route("/new", name="admin_theme_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $theme = new Theme();
        $form = $this->createForm('CommonBundle\Form\ThemeType', $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush($theme);

            return $this->redirectToRoute('admin_theme_show', array('id' => $theme->getId()));
        }

        return $this->render('AdminBundle:theme:new.html.twig', array(
            'theme' => $theme,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a theme entity.
     *
     * @Route("/{id}", name="admin_theme_show")
     * @Method("GET")
     */
    public function showAction(Theme $theme)
    {
        $deleteForm = $this->createDeleteForm($theme);

        return $this->render('AdminBundle:theme:show.html.twig', array(
            'theme' => $theme,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing theme entity.
     *
     * @Route("/{id}/edit", name="admin_theme_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Theme $theme)
    {
        $deleteForm = $this->createDeleteForm($theme);
        $editForm = $this->createForm('CommonBundle\Form\ThemeType', $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_theme_edit', array('id' => $theme->getId()));
        }

        return $this->render('AdminBundle:theme:edit.html.twig', array(
            'theme' => $theme,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a theme entity.
     *
     * @Route("/{id}", name="admin_theme_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Theme $theme)
    {
        $form = $this->createDeleteForm($theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($theme);
            $em->flush($theme);
        }

        return $this->redirectToRoute('admin_theme_index');
    }

    /**
     * Creates a form to delete a theme entity.
     *
     * @param Theme $theme The theme entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Theme $theme)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_theme_delete', array('id' => $theme->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
