<?php

namespace KD\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Gère l'affichage de QCMs et leur gestion
 */
class QCMController extends BaseController
{
    /**
     * Affichage de tout les QCMs pour un professeur.
     */
    public function indexAction()
    {
        $listQCM = $this->getRepository('QCM')->allWithNotes();
      
        return $this->render('KDCoreBundle:QCM:index.html.twig', array('listQCM' => $listQCM));
    }
  
    /**
     * Met à jour l'état du QCM passé en paramètre
     * Un nouveau QCM 'NEW' devient ouvert aux élèves 'OPEN', puis fermé aux élèves 'CLOSED'
     * Enfin ses résultats sont accessibles 'PUBLIC'
     */
    public function changeEtatAction($id)
    {
        $qcm = $this->findOneById("QCM",$id);
        
        if($qcm == null )
            return $this->redirectWithErrorFlash("liste_qcms", "Adresse invalide");
        
        switch($qcm->getEtat()){
            case "NEW":
                $qcm->setEtat("OPEN");
                break;
            case "OPEN":
                $qcm->setEtat("CLOSED");
                break;
            case "CLOSED":
                $qcm->setEtat("PUBLIC");
                break;
            default:
                return $this->redirectWithErrorFlash("liste_qcms", "Action impossible");
        }
        
        $this->persistFlush($qcm);
        return $this->redirectWithSuccessFlash("liste_qcms", "Le changement a bien été effectué");
    }
    
    /**
     * Affiche la liste des QCMs accessibles à un élève connecté
     * Soit les QCMs ouvert qu'il doit rendre et ceux (ouverts ou pas) qu'il a rendu
     */
    public function affichageEleveAction(){
        $repositoryQCM = $this->getRepository('QCM');
        
        $submittedQCMs = $repositoryQCM->findSubmittedBy($this->getUser());
        
        $openQCMs = $repositoryQCM->findByEtat("OPEN");
        //On vérifie qu'un QCM n'ait pas été cloturé juste après qu'on l'ait chargé
        foreach ($openQCMs as $key => $qcm) 
            if($qcm->getEtat()=="CLOSED") {
                unset($openQCMs[$key]);
            }
        
        $todoQCMs = array_diff($openQCMs,$submittedQCMs);
        return $this->render('KDCoreBundle:Eleve:index.html.twig', array(
            'submittedQCMs' => $submittedQCMs,
            'todoQCMs' => $todoQCMs
                ));
    }
    
    /**
     * Récupère les questions/réponses d'un qcm pour affichage à un professeur dans une requête AJAX
     * 
     * @return Response|JsonResponse
     */
    public function detailQCMAction(Request $request)
    {
        if( !$request->isXMLHttpRequest() )
            return new Response("Erreur",400);
        
        $qcm = $this->findOneById("QCM",$request->get('id'));

        if($qcm == null)
            return new Response("Erreur dans la requete",400);

        $question_array = array();

        foreach($qcm->getQuestions() as $key => $question){
            $question_array[$key]['texte'] = $question->getTexte();

            $reponse_array = array();
            foreach($question->getReponses() as $key_reponse => $reponse){
                $reponse_array[$key_reponse]['texte'] = $reponse->getTexte();
                $reponse_array[$key_reponse]['vrai'] = $reponse->getVrai();
            }

            $question_array[$key]['reponse'] = $reponse_array;
        }

        return new JsonResponse(array(
            'title'=> json_encode($qcm->getTitre()),
            'questions'=> json_encode($question_array)
            ));
    }
}
