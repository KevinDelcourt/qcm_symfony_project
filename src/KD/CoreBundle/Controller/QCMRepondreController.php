<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\Note;
use KD\CoreBundle\Entity\User;
use KD\CoreBundle\Entity\QCM;
use KD\CoreBundle\Entity\ReponseEleve;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gère la réponse à un QCM par un élève connecté
 */
class QCMRepondreController extends BaseController
{
    /**
     * Affichage du formulaire de réponse ua QCM $id
     * Si le qcm existe, est ouvert et n'a pas déjà été rempli.
     */
    public function indexAction($id)
    {
        $qcm = $this->getRepository("QCM")->oneByIdWithEntities($id);
        
        if($qcm == null || $qcm->getEtat() != "OPEN")
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Adresse invalide");
        
        if( $this->checkQCMDejaRendu($qcm, $this->getUser()) )
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Vous avez déjà rendu ce qcm");
        
        return $this->render('KDCoreBundle:Eleve:repondre.qcm.html.twig', array('qcm' => $qcm));    
        
    }
    
    public function sendAction(Request $request)
    {
        $qcm = $this->findOneById("QCM", $request->request->get("id_qcm"));
        $reponses = $request->request->get("reponses");
        
        if($reponses == null)
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Il faut au moins une réponse."); 
        
        if($qcm == null || $qcm->getEtat() != "OPEN")
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Accès refusé");

        if( $this->checkQCMDejaRendu($qcm, $this->getUser()) )
            return $this->redirectWithErrorFlash("liste_qcms_eleve", "Vous avez déjà rendu ce qcm");
        
        $em = $this->getManager();
        
        $score = $this->createReponsesEleve($reponses, $qcm, $this->getUser(), $em);
        
        $noteEleve = new Note($score,$this->getUser(),$qcm);
        $em->persist($noteEleve);
        $em->flush();
        return $this->redirectWithSuccessFlash("liste_qcms_eleve","Réponse enregistrée");
    }
    
    /**
     * Retourne vrai si $eleve a déjà rempli le $qcm
     * 
     * @param QCM $qcm
     * @param User $eleve
     * @return boolean
     */
    private function checkQCMDejaRendu(QCM $qcm,User $eleve){
        $note = $this->getRepository('Note')->findOneBy([
            'qcm' => $qcm,
            'eleve' => $eleve,
        ]);
        
        return $note != null; 
    }
    
    /**
     * Construit l'historique des réponses d'un élève a enregistrer dans la base de données.
     * 
     * @return int la note de l'élève
     */
    private function createReponsesEleve($reponses, $qcm, $eleve, $em){
        $score = 0;
        $repositoryReponse = $this->getRepository('Reponse');
        foreach($reponses as $id => $value){
            $reponse = $repositoryReponse->findOneById($id);
            
            if (!$qcm->containsReponse($reponse)) 
                return $this->redirectWithErrorFlash("liste_qcms_eleve", "Erreur dans le formulaire");

            $score += $reponse->getScore();

            $reponseEleve = new ReponseEleve($eleve,$reponse,$qcm);
            $em->persist($reponseEleve);
        }
        
        return $score;
    }
}
