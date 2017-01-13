<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\GameRepository")
 * @UniqueEntity(fields="name", message="Le jeu  {{ value }} existe déjà.")

 */
class Game
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
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     * @Assert\Type("integer")
     * @Assert\Range(
          *      min = 0,
          *      max = 110,
          *      minMessage = "L'âge doit être supérieur à {{ limit }}",
          *      maxMessage = "L'âge ne peut pas être supérieur à  {{ limit }}"
          * )
     * @ORM\Column(name="ageMin", type="integer", length=255)
     */
    private $ageMin;

    /**
     * @var string
     *
     * @ORM\Column(name="rules", type="text", nullable=true)
     */
    private $rules;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="releaseDate", type="datetime")
     */
    private $releaseDate;

    /**
     * Many Games have Many Themes.
     * @ORM\ManyToMany(targetEntity="Theme")
     * @ORM\JoinTable(name="game_theme",
     *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="theme_id", referencedColumnName="id")}
     *      )
     */
    private $themes;


    /**
     * Many Games have Many Themes.
     * @ORM\ManyToMany(targetEntity="Type")
     * @ORM\JoinTable(name="game_type",
     *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="type_id", referencedColumnName="id")}
     *      )
     */
    private $types;

    /**
     * Many Games have Many Themes.
     * @ORM\ManyToMany(targetEntity="Publisher")
     * @ORM\JoinTable(name="game_publisher",
     *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="publisher_id", referencedColumnName="id")}
     *      )
     */
    private $publishers;

    /**
     * One Game has Many Copies.
     * @ORM\OneToMany(targetEntity="Copy", mappedBy="game")
     */
    private $copies;

    /**
     *@ORM\Column(type="string", nullable=true)
     *@Assert\File(mimeTypes={"image/png", "image/jpeg"},
     *             mimeTypesMessage="L'extension du fichier est invalide {{ type }}). Les extensions valides sont {{ types }}",
     *             maxSize="1M",
     *             maxSizeMessage="Le fichier ({{ size }} {{ suffix }}) dépasse la taille maximum autorisée ({{ limit }} {{ suffix }})")
     */
     private $image;

    public function __construct() {
        $this->features = new ArrayCollection();
    }


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
     * @return Game
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
     * Set duration
     *
     * @param float $duration
     *
     * @return Game
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }



    /**
     * Set rules
     *
     * @param string $rules
     *
     * @return Game
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Get rules
     *
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     *
     * @return Game
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Add theme
     *
     * @param \CommonBundle\Entity\Theme $theme
     *
     * @return Game
     */
    public function addTheme(\CommonBundle\Entity\Theme $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme
     *
     * @param \CommonBundle\Entity\Theme $theme
     */
    public function removeTheme(\CommonBundle\Entity\Theme $theme)
    {
        $this->themes->removeElement($theme);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Add type
     *
     * @param \CommonBundle\Entity\Type $type
     *
     * @return Game
     */
    public function addType(\CommonBundle\Entity\Type $type)
    {
        $this->types[] = $type;

        return $this;
    }

    /**
     * Remove type
     *
     * @param \CommonBundle\Entity\Type $type
     */
    public function removeType(\CommonBundle\Entity\Type $type)
    {
        $this->types->removeElement($type);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypes()
    {
        return $this->types;
    }


    /**
     * Add copy
     *
     * @param \CommonBundle\Entity\Copy $copy
     *
     * @return Game
     */
    public function addCopy(\CommonBundle\Entity\Copy $copy)
    {
        $this->copies[] = $copy;

        return $this;
    }

    /**
     * Remove copy
     *
     * @param \CommonBundle\Entity\Copy $copy
     */
    public function removeCopy(\CommonBundle\Entity\Copy $copy)
    {
        $this->copies->removeElement($copy);
    }

    /**
     * Get copies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCopies()
    {
        return $this->copies;
    }


    /**
     * Set the value of Id
     *
     * @param int id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set ageMin
     *
     * @param string $ageMin
     *
     * @return Game
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;
        return $this;
    }

    /**
     * Set the value of Many Games have Many Themes.
     *
     * @param mixed themes
     *
     * @return self
     */
    public function setThemes($themes)
    {
        $this->themes = $themes;

        return $this;
    }

    /**
     * Set the value of Many Games have Many Themes.
     *
     * @param mixed types
     *
     * @return self
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Set the value of One Game has Many Copies.
     *
     * @param mixed copies
     *
     * @return self
     */
    public function setCopies($copies)
    {
        $this->copies = $copies;
        return $this;
    }

    /**
     * Get ageMin
     *
     * @return string
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }


    /**
     * Get the value of Image
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of Image
     *
     * @param mixed image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Add publisher
     *
     * @param \CommonBundle\Entity\Publisher $publisher
     *
     * @return Game
     */
    public function addPublisher(\CommonBundle\Entity\Publisher $publisher)
    {
        $this->publishers[] = $publisher;

        return $this;
    }

    /**
     * Remove publisher
     *
     * @param \CommonBundle\Entity\Publisher $publisher
     */
    public function removePublisher(\CommonBundle\Entity\Publisher $publisher)
    {
        $this->publishers->removeElement($publisher);
    }

    /**
     * Get publishers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublishers()
    {
        return $this->publishers;
    }
}
