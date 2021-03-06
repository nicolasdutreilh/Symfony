<?php
// src/OC/PlatformBundle/Entity/Advert.php

namespace OC\PlatformBundle\Entity;

//on définit le namespace des anotations utilisées par Doctrine2
//en effet, il existe d'autres annotations, on le verra par la suite,
// qui utiliseront un autre namespace
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use OC\UserBundle\Entity\User;

/**
 * Advert
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\AdvertRepository")
 * @ORM\Table(name="advert")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une annonce existe déjà avec ce titre.")
 */
class Advert
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
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\Length(min=10)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\OneToOne(targetEntity="OC\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    public $categories;

    /**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications; //une annonce est liée à plusieurs candidatures (applicationS)

    /**
     *
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\AdvertSkill", mappedBy="advert")
     */
    private $skills;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
     private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="OC\UserBundle\Entity\User")
     * @@ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;
    /**
     * @ORM\Column(name="nb_applications", type="integer")
     */
    private $nbApplications = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=false, nullable=true)
     */
    private $slug;


    /**
     * @Assert\IsTrue()
     */
    public function isTitle()
    {
      return false;
    }

    /**
     * @Assert\Callback
     */
    public function isContentValid(ExecutionContextInterface $context)
    {
      $forbiddenWords = array('échec', 'abandon');

      //on vérifie que le contenu ne contient pas l'un des mots
      if (preg_match('#'.implode('|', $forbiddenWords).'#', $this->getContent())) {
        //la règle est violée, on définit l'erreur
        $context
          ->buildViolation('Contenu invalide car il contient un mot interdit.') //message
          ->atPath('content') //attribut de l'objet qui est violé
          ->addViolation() //déclenche l'erreur
        ;
      }
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     *
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image
     *
     * @param \OC\PlatformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\OC\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \OC\PlatformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add category
     *
     * @param \OC\PlatformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(\OC\PlatformBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \OC\PlatformBundle\Entity\Category $category
     */
    public function removeCategory(\OC\PlatformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add application
     *
     * @param \OC\PlatformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\OC\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;

        return $this;
    }

    /**
     * Remove application
     *
     * @param \OC\PlatformBundle\Entity\Application $application
     */
    public function removeApplication(\OC\PlatformBundle\Entity\Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add skill
     *
     * @param \OC\PlatformBundle\Entity\AdvertSkill $skill
     *
     * @return Advert
     */
    public function addSkill(\OC\PlatformBundle\Entity\AdvertSkill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \OC\PlatformBundle\Entity\AdvertSkill $skill
     */
    public function removeSkill(\OC\PlatformBundle\Entity\AdvertSkill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set author
     *
     * @param \OC\UserBundle\Entity\User $author
     *
     * @return Advert
     */
    public function setAuthor(\OC\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \OC\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
