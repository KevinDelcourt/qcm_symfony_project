<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\ReponseRepository")
 */
class Reponse
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
     * @var bool
     *
     * @ORM\Column(name="vrai", type="boolean")
     */
    private $vrai;

    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\Question",inversedBy="reponses")
    * @ORM\JoinColumn(nullable=false)
    */
    private $question;

    public function __construct($texte, $vrai, \KD\CoreBundle\Entity\Question $question) {
        
        $this->texte = $texte;
        $this->vrai = $vrai;
        $this->question = $question;
    }
    
    /**
     * Retourne le score d'une réponse -> 2 si elle est vraie, -1 sinon
     * @return int
     */
    public function getScore(){
        if($this->vrai)
            return +2;
        else
            return -1;
    }
    
    /**
     * Vrai si cette réponse est contenue dans un tableau de réponses $reponsesEleve
     * 
     * @param array $reponsesEleve
     * @return boolean
     */
    public function isPresent(array $reponsesEleve){
        foreach($reponsesEleve as $entree)
            if($entree->getReponse() == $this)
                return true;
        return false;
    }
    
    /**
     * Retourne le score d'un élève à cette réponse d'après son tableau de réponses:
     * 0 si il n'a pas coché cette réponse
     * le score de la question (+2 ou -1) si il a coché cette réponse
     * 
     * @param array $reponsesEleve
     * @return int
     */
    public function getScoreIfPresent(array $reponsesEleve){
        if($this->isPresent($reponsesEleve))
            return $this->getScore ();
        else
            return 0;
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
     * @return Reponse
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
     * Set vrai
     *
     * @param boolean $vrai
     *
     * @return Reponse
     */
    public function setVrai($vrai)
    {
        $this->vrai = $vrai;

        return $this;
    }

    /**
     * Get vrai
     *
     * @return bool
     */
    public function getVrai()
    {
        return $this->vrai;
    }

    /**
     * Set question
     *
     * @param \KD\CoreBundle\Entity\Question $question
     *
     * @return Reponse
     */
    public function setQuestion(\KD\CoreBundle\Entity\Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \KD\CoreBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
