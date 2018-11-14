<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \KD\CoreBundle\Entity\Theme;
use \KD\CoreBundle\Entity\User;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\Theme",inversedBy="questions")
    * @ORM\JoinColumn(nullable=false)
    */
    private $theme;

    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $auteur;
    
    /**
     * @ORM\ManyToMany(targetEntity="KD\CoreBundle\Entity\QCM", mappedBy="questions")
     */
    private $qcms;
    
     /**
     * @ORM\OneToMany(targetEntity="KD\CoreBundle\Entity\Reponse", mappedBy="question", cascade={"persist", "remove"})
     */
    private $reponses;
    
    public function __construct($texte, Theme $theme, User $auteur) {
        $this->texte = $texte;
        $this->theme = $theme;
        $this->auteur = $auteur;
    }
    
    /**
     * Une question ne peut être modifiée/supprimée que si elle n'est pas utilisée par un QCM
     * 
     * @return boolean
     */
    public function isEditable(){
        return count($this->qcms->toArray()) == 0;
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
     * Set texte
     *
     * @param string $texte
     *
     * @return Question
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set theme
     *
     * @param \KD\CoreBundle\Entity\Theme $theme
     *
     * @return Question
     */
    public function setTheme(Theme $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \KD\CoreBundle\Entity\Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set auteur
     *
     * @param \KD\CoreBundle\Entity\User $auteur
     *
     * @return Question
     */
    public function setAuteur(User $auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \KD\CoreBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set reponses
     *
     * @param \KD\CoreBundle\Entity\Reponse $reponses
     *
     * @return Question
     */
    public function setReponses(\KD\CoreBundle\Entity\Reponse $reponses = null)
    {
        $this->reponses = $reponses;

        return $this;
    }

    /**
     * Get reponses
     *
     * @return \KD\CoreBundle\Entity\Reponse
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * Add reponse
     *
     * @param \KD\CoreBundle\Entity\Reponse $reponse
     *
     * @return Question
     */
    public function addReponse(\KD\CoreBundle\Entity\Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        return $this;
    }

    /**
     * Remove reponse
     *
     * @param \KD\CoreBundle\Entity\Reponse $reponse
     */
    public function removeReponse(\KD\CoreBundle\Entity\Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }

    /**
     * Add qcm
     *
     * @param \KD\CoreBundle\Entity\QCM $qcm
     *
     * @return Question
     */
    public function addQcm(\KD\CoreBundle\Entity\QCM $qcm)
    {
        $this->qcms[] = $qcm;

        return $this;
    }

    /**
     * Remove qcm
     *
     * @param \KD\CoreBundle\Entity\QCM $qcm
     */
    public function removeQcm(\KD\CoreBundle\Entity\QCM $qcm)
    {
        $this->qcms->removeElement($qcm);
    }

    /**
     * Get qcms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQcms()
    {
        return $this->qcms;
    }
}
