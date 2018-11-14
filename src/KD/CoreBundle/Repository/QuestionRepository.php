<?php

namespace KD\CoreBundle\Repository;

class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *  Retourne les questions et charge les entités nécessaires à leur affichage
     *  (l'auteur, le thème et les qcms (utilsés par Question->isEditable())
     */
    public function allWithEntities(){
        
        $query = $this->_em->createQuery("SELECT q, a, t, qcm "
                . "FROM KDCoreBundle:Question q "
                . "JOIN q.auteur a "
                . "JOIN q.theme t "
                . "LEFT JOIN q.qcms qcm");

        return $query->getResult();
    }
    
    /**
     * Même que ci-dessus, mais ne retourne que les questions de thème $id_theme
     */
    public function findByThemeWithEntities($id_theme){
        
        $query = $this->_em->createQuery("SELECT q, a, t, qcm "
                . "FROM KDCoreBundle:Question q "
                . "JOIN q.auteur a "
                . "JOIN q.theme t "
                . "LEFT JOIN q.qcms qcm "
                . "WHERE t.id = ?1");
        
        $query->setParameter(1,$id_theme);

        return $query->getResult();
    }
    
    /**
     * Retourne une question $id_question 
     * Charge les entités nécessaires à son affichage/édition
     */
    public function oneByIdWithEntities($id_question){
        
        $query = $this->_em->createQuery("SELECT q, t, r, qcm "
                . "FROM KDCoreBundle:Question q "
                . "JOIN q.theme t "
                . "JOIN q.reponses r "
                . "LEFT JOIN q.qcms qcm "
                . "WHERE q.id = ?1");
        
        $query->setParameter(1,$id_question);

        return $query->getOneOrNullResult();
    }
}
