<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="KD\CoreBundle\Entity\Question", mappedBy="theme")
     */
    private $questions;
    
    public function __construct($nom)
    {
        $this->nom = $nom;
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getQuestionCount(){
        return count($this->getQuestions()->toArray());
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Theme
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Add question
     *
     * @param \KD\CoreBundle\Entity\Question $question
     *
     * @return Theme
     */
    public function addQuestion(\KD\CoreBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \KD\CoreBundle\Entity\Question $question
     */
    public function removeQuestion(\KD\CoreBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
