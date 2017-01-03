<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Member controller.
 *
 * @Route("admin/member")
 */
class MemberController extends Controller
{
    /**
     * Lists all member entities.
     *
     * @Route("/", name="admin_member_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $members = $em->getRepository('CommonBundle:Member')->findAll();

        return $this->render('AdminBundle:member:index.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * Creates a new member entity.
     *
     * @Route("/new", name="admin_member_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $member = new Member();
        $form = $this->createForm('CommonBundle\Form\MemberType', $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush($member);

            return $this->redirectToRoute('admin_member_show', array('id' => $member->getId()));
        }

        return $this->render('AdminBundle:member:new.html.twig', array(
            'member' => $member,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a member entity.
     *
     * @Route("/{id}", name="admin_member_show")
     * @Method("GET")
     */
    public function showAction(Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);

        return $this->render('AdminBundle:member:show.html.twig', array(
            'member' => $member,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing member entity.
     *
     * @Route("/{id}/edit", name="admin_member_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);
        $editForm = $this->createForm('CommonBundle\Form\MemberType', $member);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_member_edit', array('id' => $member->getId()));
        }

        return $this->render('AdminBundle:member:edit.html.twig', array(
            'member' => $member,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Lists all members entities.
     *
     * @Route("/json/member", name="members_resp" )
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


        if( strlen($search)<2) {
            $members = $em->getRepository('CommonBundle:Member')->findAllByArg($order, $offset, $limit);
            $nbRows = $em->getRepository('CommonBundle:Member')->countAll();
            dump($members);
            dump($nbRows);
        } else {
            $members = $em->getRepository('CommonBundle:Member')->findBySearch($search,$order,$offset,$limit);
            $nbRows = $em->getRepository('CommonBundle:Member')->countAllBySearch($search);
            dump($members);
            dump($nbRows);
        }
        die;
        $rows =[];
//        foreach ($histodemandes as $histodemande) {
//            $line['id'] = $histodemande->getId();
//            $line['demandeur'] = $histodemande->getDemandeur();
//            $line['commentaire'] = $histodemande->getCommentaire();
//            $line['projet'] = $histodemande->getProjet();
//            $line['nom'] = $histodemande->getNom();
//            $line['statut'] = $histodemande->getStatut();
//            $line['date'] = $histodemande->getDateDemande()->format('d-m-Y H:i:s');
//            $rows[] = $line;
//        }

//
//        $result['total'] = sizeof($nbRows);
//        $result['rows'] = $rows;

        return new JsonResponse('test');
    }

    /**
     * Deletes a member entity.
     *
     * @Route("/{id}", name="admin_member_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Member $member)
    {
        $form = $this->createDeleteForm($member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($member);
            $em->flush($member);
        }

        return $this->redirectToRoute('admin_member_index');
    }

    /**
     * Creates a form to delete a member entity.
     *
     * @param Member $member The member entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Member $member)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_member_delete', array('id' => $member->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
