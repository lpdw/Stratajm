<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Copy
 *
 * @ORM\Table(name="copy")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CopyRepository")
 */
class Copy
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
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * Many Copies have One Game.
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="copies")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * Many Copies have One status.
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * Many Copies have One Localisation.
     * @ORM\ManyToOne(targetEntity="Localisation")
     * @ORM\JoinColumn(name="localisation_id", referencedColumnName="id")
     */
    private $localisation;




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
     * Set reference
     *
     * @param string $reference
     *
     * @return Copy
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set game
     *
     * @param \CommonBundle\Entity\Game $game
     *
     * @return Copy
     */
    public function setGame(\CommonBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \CommonBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set status
     *
     * @param \CommonBundle\Entity\Status $status
     *
     * @return Copy
     */
    public function setStatus(\CommonBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \CommonBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set localisation
     *
     * @param \CommonBundle\Entity\Localisation $localisation
     *
     * @return Copy
     */
    public function setLocalisation(\CommonBundle\Entity\Localisation $localisation = null)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return \CommonBundle\Entity\Localisation
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }
}
