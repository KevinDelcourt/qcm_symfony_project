<?php

namespace KD\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Gère l'affichage de la liste des questions/réponses
 */
class QuestionController extends BaseController
{
    /**
     * Affiche la liste de toutes les questions non-triées
     */
    public function indexAction()
    {
        $listQuestions = $this->getRepository('Question')->allWithEntities();
        $listThemes = $this->getRepository('Theme')->findAll();
        
        return $this->render('KDCoreBundle:Question:index.html.twig', array(
            'listQuestions' => $listQuestions,
            'listThemes' => $listThemes,
            'theme' => null
                ));
    }
    
    /**
     * Affiche la liste des questions filtrer par le thème défini par $id_theme
     */
    public function filtrerThemeAction($id_theme)
    {
        $selectedTheme = $this->findOneById("Theme",$id_theme);
        
        if($selectedTheme == null)
            return $this->redirectWithErrorFlash ("questions", "Adresse invalide");
        
        $listQuestions = $this->getRepository('Question')->findByThemeWithEntities($selectedTheme->getId());
        $listThemes = $this->getRepository('Theme')->findAll();
        return $this->render('KDCoreBundle:Question:index.html.twig', array(
            'listQuestions' => $listQuestions,
            'listThemes' => $listThemes,
            'theme' => $selectedTheme
                ));
    }
    
    /**
     * Récupère les réponses d'une question pour affichage à un professeur dans une requête AJAX
     * 
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function detailQuestionAction(Request $request)
    {
        if( !$request->isXMLHttpRequest())
            return new Response("Erreur",400);
        
        $question = $this->findOneById("Question", $request->get('id'));
        if($question == null)
            return new Response("Erreur",400);

        $reponses = $question->getReponses();
        $reponse_array = array();

        foreach($reponses as $key => $reponse){
            $reponse_array[$key]['texte'] = $reponse->getTexte();
            $reponse_array[$key]['vrai'] = $reponse->getVrai();
        }

        return new JsonResponse(array(
            'title'=> json_encode($question->getTexte()),
            'reponses'=> json_encode($reponse_array)
            ));
        
    }
}
