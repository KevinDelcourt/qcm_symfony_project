<?php

namespace KD\CoreBundle\Repository;

class NoteRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Retourne un tableau contenant la moyenne, note minimum et note maximum d'un QCM
     */
    public function getStatsFromQCM($id_qcm){
        return $this->createQueryBuilder("n")
            ->select("AVG(n.valeur) as noteMoy, MIN(n.valeur) as noteMin, MAX(n.valeur) as noteMax")
            ->join("n.qcm", "qcm")
            ->groupBy("qcm")
            ->where("qcm.id = ?1")
            ->setParameter(1,$id_qcm)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
