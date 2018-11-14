<?php

namespace KD\CoreBundle\Repository;

class ThemeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Récupère la liste des thèmes (id et nom) avec le nombre de questions associées.
     * Utile pour l'affichage
     */
    public function allThemesWithQuestionCount(){
        return $this->createQueryBuilder("t")
            ->select("COUNT(question) as nb, t.nom, t.id")
            ->leftJoin("t.questions", "question")
            ->groupBy("t")
            ->orderBy("t.nom","ASC")
            ->getQuery()
            ->getResult();
    }
}
