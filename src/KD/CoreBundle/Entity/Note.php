<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \KD\CoreBundle\Entity\User;
use \KD\CoreBundle\Entity\QCM;
/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\NoteRepository")
 */
class Note
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
     * @var int
     *
     * @ORM\Column(name="valeur", type="integer")
     */
    private $valeur;

    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\User",inversedBy="notes")
    * @ORM\JoinColumn(nullable=false)
    */
    private $eleve;

    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\QCM",inversedBy="notes")
    * @ORM\JoinColumn(nullable=false)
    */
    private $qcm;
    
    public function __construct($valeur, User $eleve, QCM $qcm) {
        $this->valeur = $valeur;
        $this->eleve = $eleve;
        $this->qcm = $qcm;
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
     * Set valeur
     *
     * @param integer $valeur
     *
     * @return Note
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return int
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set eleve
     *
     * @param \KD\CoreBundle\Entity\User $eleve
     *
     * @return Note
     */
    public function setEleve(User $eleve)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return \KD\CoreBundle\Entity\User
     */
    public function getEleve()
    {
        return $this->eleve;
    }

    /**
     * Set qcm
     *
     * @param \KD\CoreBundle\Entity\QCM $qcm
     *
     * @return Note
     */
    public function setQcm(QCM $qcm)
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Get qcm
     *
     * @return \KD\CoreBundle\Entity\QCM
     */
    public function getQcm()
    {
        return $this->qcm;
    }
}
