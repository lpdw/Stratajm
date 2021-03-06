<?php

namespace CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Member = membres de l'association
 *
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\MemberRepository")
 * @UniqueEntity(fields={"firstName", "lastName"},message="L'utilisateur {{ value }} existe déjà.")
 * @UniqueEntity(fields="email", message="L'email {{ value }} est déjà utilisé.")


 */
class Member
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
     * @Assert\NotBlank()
     * @Assert\Type("string")
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true,
     *     checkHost=true
     * )
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\Type("string")

     * @Assert\Regex(
     *      pattern="/^[0-9]*$/",
     *      message="Utiliser seulement des nombres (ex : 0120304050)"
     * )

     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Le numéro doit faire {{ limit }} caractère (ex : 0120304050)",
     * )
     * @ORM\Column(name="telNum", type="string", nullable=true, unique=true)
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telNum
     *
     * @param integer $telNum
     *
     * @return User
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


    /**
     * Add membership
     *
     * @param \CommonBundle\Entity\Membership $membership
     *
     * @return Member
     */
    public function addMembership(\CommonBundle\Entity\Membership $membership)
    {
        $this->memberships[] = $membership;

        return $this;
    }


}
