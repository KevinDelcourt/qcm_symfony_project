<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \KD\CoreBundle\Entity\Reponse;

/**
 * QCM
 *
 * @ORM\Table(name="q_c_m")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\QCMRepository")
 * @ORM\HasLifecycleCallbacks
 */
class QCM
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;
    
    /**
     * @ORM\ManyToMany(targetEntity="KD\CoreBundle\Entity\Question",inversedBy="qcms")
     */
    private $questions;
    
    /**
     * @ORM\OneToMany(targetEntity="KD\CoreBundle\Entity\Note", mappedBy="qcm")
     */
    private $notes;
    
    //NEW -> OPEN -> CLOSED -> PUBLIC
    public function __construct($titre, $deadline) {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->titre = $titre;
        $this->deadline = $deadline;
        $this->etat = "NEW";
    }
   /**
    * Retourne le score maximum possible pour un qcm
    * Utilisé pour la notation
    * 
    * @return int
    */
    public function getScoreMax(){
        $score = 0;
        
        $array_questions = $this->questions->toArray();
        foreach($array_questions as $q){
            $array_reponses = $q->getReponses()->toArray();
            foreach($array_reponses as $r)
                if($r->getVrai())
                    $score += 2;
            
        }
        return $score;
    }
    /**
     * Retourne true si la réponse en paramètre est sensée être dans le QCM
     * Retourne faux sinon (ou si $reponse == null)
     * 
     * @param Reponse $reponse
     * @return boolean
     */
    public function containsReponse(Reponse $reponse){
        $contains = false;

        $array_questions = $this->questions->toArray();
        foreach($array_questions as $q){
            $array_reponses = $q->getReponses()->toArray();
            foreach($array_reponses as $r)
                $contains = $contains || $r == $reponse;
            
        }
        return $contains;
    }
    
    /**
     * Vrai si $eleve a déjà répondu à ce QCM
     * 
     * @param \KD\CoreBundle\Entity\User $eleve
     * @return boolean
     */
    public function submittedBy(User $eleve){
        
        $array_notes = $this->notes->toArray();
        foreach($array_notes as $note)
            if($note->getEleve() == $eleve)
                return true;
            
        return false;
    }
    
    /** 
     * A chaque fois qu'un QCM est chargé (@PostLoad) on vérifie que la date limite ne soit pas dépassée
     * 
     * @ORM\PostLoad 
     */
    public function checkDeadline(){
        if($this->etat == "OPEN" && $this->deadline->diff(new \DateTime())->invert == 0)
            $this->etat = "CLOSED";   
    }
    
    /**
     * Affichage de l'état pour utilisateur.
     * @return string
     */
    public function printEtat(){
        switch ($this->etat) {
            case "NEW":
                return "Nouveau";
            case "OPEN":
                return "En cours";     
            case "CLOSED":
                return "Terminé";
            case "PUBLIC":
                return "Résultats publiés";
            default:
                return "Erreur";
        }
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
     * Set titre
     *
     * @param string $titre
     *
     * @return QCM
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return QCM
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Add question
     *
     * @param \KD\CoreBundle\Entity\Question $question
     *
     * @return QCM
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

    /**
     * Add note
     *
     * @param \KD\CoreBundle\Entity\Note $note
     *
     * @return QCM
     */
    public function addNote(\KD\CoreBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \KD\CoreBundle\Entity\Note $note
     */
    public function removeNote(\KD\CoreBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return QCM
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }
    
    public function __toString() {
        return "".$this->id;
    }
}
