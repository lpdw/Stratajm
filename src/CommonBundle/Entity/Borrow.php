<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Borrow
 *
 * @ORM\Table(name="borrow")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\BorrowRepository")
 */
class Borrow
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
     * @var \DateTime
     *
     * @ORM\Column(name="beginDate", type="datetime")
     */
    private $beginDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * Many Borrow have One Copy.
     * @ORM\ManyToOne(targetEntity="Copy")
     * @ORM\JoinColumn(name="copy_id", referencedColumnName="id")
     */
    private $copy;

    /**
     * Many Borrow have One Member.
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ongoing", type="boolean")
     */
    private $onGoing;


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
     * Set beginDate
     *
     * @param \DateTime $beginDate
     *
     * @return Borrow
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    /**
     * Get beginDate
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Borrow
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set copy
     *
     * @param \CommonBundle\Entity\Copy $copy
     *
     * @return Borrow
     */
    public function setCopy(\CommonBundle\Entity\Copy $copy = null)
    {
        $this->copy = $copy;

        return $this;
    }

    /**
     * Get copy
     *
     * @return \CommonBundle\Entity\Copy
     */
    public function getCopy()
    {
        return $this->copy;
    }



    /**
     * Set member
     *
     * @param \CommonBundle\Entity\Member $member
     *
     * @return Borrow
     */
    public function setMember(\CommonBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \CommonBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Get onGoing
     *
     * @return boolean
     */
    public function getOnGoing()
    {
        return $this->onGoing;
    }


    /**
     * Set onGoing
     *
     * @param boolean $onGoin
     *
     * @return Borrow
     */
    public function setOnGoing($onGoing)
    {
        $this->onGoing = $onGoing;

        return $this;
    }




}
