<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Member;
use CommonBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $members = $em->getRepository('CommonBundle:Member')->findAll();

        $membership = new Membership();
        $form = $this->createForm('CommonBundle\Form\MembershipType', $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idMember = $form->get('memberId')->getData();
            $member = $em->getRepository('CommonBundle:Member')->findById($idMember);
            $membership->setMember($member[0]);
            $em->persist($membership);
            $em->flush($membership);

//            return $this->redirectToRoute('admin_membership_show', array('id' => $membership->getId()));
        }



        return $this->render('AdminBundle:member:index.html.twig', array(
            'members' => $members,
            'membership' => $membership,
            'form' => $form->createView(),
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
     * @Route("/{id}", name="admin_member_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Member $member)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($member);
        $memberships = $em->getRepository('CommonBundle:Membership')->findByMember($member->getId());

        return $this->render('AdminBundle:member:show.html.twig', array(
            'memberships' => $memberships,
            'member' => $member,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing member entity.
     *
     * @Route("/{id}/edit", name="admin_member_edit", options={"expose"=true})
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
     * A mettre en service, return true si la date passé en param date d'il y a plus d'un an
     */
    public function datetimeAction($date){
//        $date = $request->query->get('date');
        if($date != null){
            $dateNow = new \DateTime();
            $dateMax = new \DateTime($date[1]);
            if($interval = $dateNow->diff($dateMax)->days > 365){
                return "true";
            } else {
                return "false";
            }
        }

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
        } else {
            $members = $em->getRepository('CommonBundle:Member')->findBySearch($search,$order,$offset,$limit);
            $nbRows = $em->getRepository('CommonBundle:Member')->countAllBySearch($search);
        }
        $rows =[];
        foreach ($members as $member) {

            $date = $em->getRepository('CommonBundle:Membership')->findMaxByMember($member);

            $line['id'] = $member->getId();
            $line['firstname'] = $member->getFirstName();
            $line['lastname'] = $member->getLastName();
            $line['telNum'] = $member->getTelNum();
            $line['email'] = $member->getEmail();
            $line['lastDate'] = self::datetimeAction($date);
            $rows[] = $line;
        }

        $result['total'] = $nbRows;
        $result['rows'] = $rows;

        return new JsonResponse($result);
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
