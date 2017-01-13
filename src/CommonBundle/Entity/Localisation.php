<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Localisation
 *
 * @ORM\Table(name="localisation")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\LocalisationRepository")
 * @UniqueEntity(fields="name", message="L'emplacement {{ value }} existe déjà.")
 * @UniqueEntity(fields="adress", message="L'adresse {{ value }} est déjà utilisée.")

 */
class Localisation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255)
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="telNum", type="string", nullable=true)
     */
    private $telNum;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Localisation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Localisation
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set telNum
     *
     * @param integer $telNum
     *
     * @return Localisation
     */
    public function setTelNum($telNum)
    {
        $this->telNum = $telNum;

        return $this;
    }

    /**
     * Get telNum
     *
     * @return int
     */
    public function getTelNum()
    {
        return $this->telNum;
    }
}
