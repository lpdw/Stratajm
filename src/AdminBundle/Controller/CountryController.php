<?php

namespace AdminBundle\Controller;

use CommonBundle\Entity\Country;
use AdminBundle\Services\CopyGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Country controller.
 *
 * @Route("admin/country")
 */
class CountryController extends Controller
{
    /**
     * Lists all country entities.
     *
     * @Route("/", name="admin_country_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('CommonBundle:Country')->findAll();

        return $this->render('AdminBundle:country:index.html.twig', array(
            'countries' => $countries,
        ));
    }

    /**
     * Finds and displays a country entity.
     *
     * @Route("/{id}", name="admin_country_show")
     * @Method("GET")
     */
    public function showAction(Country $country)
    {

        return $this->render('AdminBundle:country:show.html.twig', array(
            'country' => $country,
        ));
    }
    /**
     * Creates a new country entity.
     *
     * @Route("/new", name="admin_country_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $country = new Country();
        $form = $this->createForm('CommonBundle\Form\CountryType', $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush($country);

            return $this->redirectToRoute('admin_country_show', array('id' => $country->getId()));
        }

        return $this->render('AdminBundle:country:new.html.twig', array(
            'country' => $country,
            'form' => $form->createView(),
        ));
    }

}
