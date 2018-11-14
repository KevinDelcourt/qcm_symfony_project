<?php

namespace KD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \KD\CoreBundle\Entity\Reponse;
use \KD\CoreBundle\Entity\User;
use \KD\CoreBundle\Entity\QCM;
/**
 * ReponseEleve
 *
 * @ORM\Table(name="reponse_eleve")
 * @ORM\Entity(repositoryClass="KD\CoreBundle\Repository\ReponseEleveRepository")
 */
class ReponseEleve
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
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $eleve;
    
    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\Reponse")
    * @ORM\JoinColumn(nullable=false)
    */
    private $reponse;
    
    /**
    * @ORM\ManyToOne(targetEntity="KD\CoreBundle\Entity\QCM")
    * @ORM\JoinColumn(nullable=false)
    */
    private $qcm;

    public function __construct(User $eleve, Reponse $reponse, QCM $qcm) {
        $this->eleve = $eleve;
        $this->reponse = $reponse;
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
     * Set eleve
     *
     * @param \KD\CoreBundle\Entity\User $eleve
     *
     * @return ReponseEleve
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
     * Set reponse
     *
     * @param \KD\CoreBundle\Entity\Reponse $reponse
     *
     * @return ReponseEleve
     */
    public function setReponse(Reponse $reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return \KD\CoreBundle\Entity\Reponse
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set qcm
     *
     * @param \KD\CoreBundle\Entity\QCM $qcm
     *
     * @return ReponseEleve
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
