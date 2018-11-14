<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\QCM;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gère la création/suppression d'un QCM
 */
class QCMCreationController extends BaseController
{
    /**
     * Formulaire de création de QCM
     */
    public function indexAction()
    {
        $listQuestions = $this->getRepository('Question')->allWithEntities();
        return $this->render('KDCoreBundle:QCM:creation.html.twig', array('listQuestions' => $listQuestions));    
    }
    
    /**
     * Traitement du formulaire de création de QCM.
     * 
     * @param Request $request
     */
    public function addAction(Request $request)
    {
        $titre = $request->request->get("titre");
        $questionIds = $request->request->get("questions");
        $deadline = \DateTime::createFromFormat(
                "Y-m-d G:i:s",
                $request->request->get("deadline")." 23:55:00"
                );

        if( $titre == null || $questionIds == null || $deadline == null )
            return $this->redirectWithErrorFlash("create_qcm", "Veuillez renseigner tout les champs");
        
        if( $deadline->diff(new \DateTime())->invert == 0 )//= $deadline est avant maintenant
            return $this->redirectWithErrorFlash("create_qcm", "Veuillez entrer une date future");
        
        $qcm = new QCM($titre,$deadline);
        
        $questionRepository = $this->getRepository('Question');
        foreach($questionIds as $id){
            $question = $questionRepository->findOneById($id);
            if($question == null)
                return $this->redirectWithErrorFlash("create_qcm", "Des questions sélectionnées sont inconnues");

            $qcm->addQuestion($question);
        }
        
        $this->persistFlush($qcm);
        return $this->redirectWithSuccessFlash("liste_qcms", "Le QCM a bien été créé");
    }
    
    /**
     * Suppression d'un QCM avec l'$id passé en paramètre
     */
    public function deleteAction($id)
    {
        $qcm = $this->findOneById("QCM",$id);
        
        //on autorise la suppression d'un QCM que si il n'est pas encore ouvert aux élèves
        if($qcm == null || $qcm->getEtat()!="NEW")
            return $this->redirectWithErrorFlash("liste_qcms", "Adresse invalide");
        
        $this->removeFlush($qcm);
        return $this->redirectWithSuccessFlash("liste_qcms", "QCM supprimé avec succès");
    }
}
