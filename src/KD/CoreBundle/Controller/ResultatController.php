<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\User;
use KD\CoreBundle\Entity\QCM;

/**
 * Gère l'affichage des résultats à un QCM, du point de vue du professeur ou de l'élève
 */
class ResultatController extends BaseController
{
    /**
     * Affiche toutes les notes d'un QCM $id_qcm pour un professeur
     */
    public function affichageQCMAction($id_qcm)
    {
        $qcm = $this->getRepository("QCM")->oneByIdWithEntities($id_qcm);
        if($qcm == null)
            return $this->redirectWithErrorFlash("resultats", "Adresse invalide");
        
        $notes = $qcm->getNotes();
        if(count($notes) == 0)
            return $this->redirectWithErrorFlash("resultats", "Pas de note à aficher");
        
        return $this->render('KDCoreBundle:Resultat:qcm.html.twig', array(
            'qcm' => $qcm,
            'notes'=>$notes
            ));
    }
    
    /**
     * Cas où un professeur veut afficher le détail des réponses d'un élève $id_eleve au QCM $id_qcm
     */
    public function detailEleveAction($id_qcm,$id_eleve)
    {
        $qcm = $this->getRepository("QCM")->oneByIdWithEntities($id_qcm);
        $eleve = $this->findOneById("User",$id_eleve);
        
        if($eleve == null || $qcm == null)
            return $this->redirectWithErrorFlash("resultats", "Adresse invalide");
        
        return $this->affichageResultat($qcm, $eleve, "resultats");
    }
    
    /**
     * Cas où un élève connecté veut consulter ses résultats au QCM $id
     */
    public function affichageEleveConnecteAction($id)
    {
        $qcm = $this->findOneById("QCM",$id);
        
        if($qcm == null || $qcm->getEtat() != "PUBLIC")
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Adresse invalide");
        
        return $this->affichageResultat($qcm, $this->getUser(), "liste_qcms_eleve");
        
    }
    /**
     * Affiche le détail des notes d'un élève à un QCM
     * 
     * @param QCM $qcm
     * @param User $eleve
     * @param $escapeRoute la route à rediriger en cas d'erreur
     * @return Response
     */
    private function affichageResultat(QCM $qcm,User $eleve, $escapeRoute)
    {
        $note = $this->getRepository('Note')->findOneBy([
            'qcm' => $qcm,
            'eleve' => $eleve,
        ]);
        
        $reponsesEleve = $this->getRepository('ReponseEleve')->findBy([
            'qcm' => $qcm,
            'eleve' => $eleve,
        ]);
        
        if($note == null || $reponsesEleve == null)
            return $this->redirectWithErrorFlash($escapeRoute, "Adresse invalide");
        
        return $this->render('KDCoreBundle:Resultat:detail.html.twig', array(
            'qcm'   => $qcm,
            'eleve' => $eleve,
            'note'  => $note,
            'reponsesEleve' => $reponsesEleve
                ));

    }
}
