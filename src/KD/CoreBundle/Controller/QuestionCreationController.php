<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\Reponse;
use KD\CoreBundle\Entity\Question;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gère la création/modification/suppression d'une question
 */
class QuestionCreationController extends BaseController
{
    /**
     * Affiche le formulaire de création d'une question
     */
    public function indexAction()
    {
        $listThemes = $this->getRepository('Theme')->findAll();
    
        return $this->render('KDCoreBundle:Question:creation.html.twig', array(
            'listThemes' => $listThemes,
            'question' => null,
            'selectedTheme' => null,
            'copy' => false
                ));
    }
    
    /**
     * Affiche le formulaire de création d'une question, avec un thème choisi
     */
    public function indexWithThemeAction($id_theme)
    {
        $repositoryTheme = $this->getRepository("Theme");
        
        $listThemes = $repositoryTheme->findAll();
        $selectedTheme = $repositoryTheme->findOneById($id_theme);
        
        if($selectedTheme == null)
            return $this->redirectWithErrorFlash("themes","Adresse invalide");
    
        return $this->render('KDCoreBundle:Question:creation.html.twig', array(
            'listThemes' => $listThemes,
            'question' => null,
            'selectedTheme' => $selectedTheme,
            'copy' => false
                ));
    }
    
    /**
     * Affiche le formulaire de création d'une question, avec les informations
     * de la question $id déjà renseignées pour modification.
     */
    public function editAction($id)
    {
        $listThemes = $this->getRepository('Theme')->findAll();        
        $question = $this->getRepository("Question")->oneByIdWithEntities($id);
        
        if($question == null || !$question->isEditable())
            return $this->redirectWithErrorFlash("questions", "Adresse invalide");
        
        return $this->render('KDCoreBundle:Question:creation.html.twig', array(
            'listThemes' => $listThemes,
            'question'   => $question,
            'selectedTheme' => $question->getTheme(),
            'copy' => false    
                ));
    }
    
    /**
     * Affiche le formulaire de création d'une question, avec les informations
     * de la question $id déjà renseignées pour copie.
     * Ne supprimera pas la question copiée.
     */
    public function copyAction($id)
    {
        $listThemes = $this->getRepository('Theme')->findAll();        
        $question = $this->getRepository("Question")->oneByIdWithEntities($id);
        
        if($question == null)
            return $this->redirectWithErrorFlash("questions", "Adresse invalide");
        
        return $this->render('KDCoreBundle:Question:creation.html.twig', array(
            'listThemes' => $listThemes,
            'question'   => $question,
            'selectedTheme' => $question->getTheme(),
            'copy' => true    
                ));
    }
    
    /**
     * Supprime une question $id après vérification
     */
    public function deleteAction($id)
    {
        $question = $this->findOneById("Question",$id);
        
        if($question == null || !$question->isEditable())
            return $this->redirectWithErrorFlash("questions", "Adresse invalide");
        
        $this->removeFlush($question);
        return $this->redirectWithSuccessFlash("questions", "Question supprimée avec succès");
    }
    
    /**
     * Traitement du formulaire de création de question
     * @param Request $request
     */
    public function addAction(Request $request)
    {
        $texteQuestion = $request->request->get("texteQuestion");
        $reponses = $request->request->get("reponse");
        $vrais = $request->request->get("vrai");
        $themeQuestion = $this->findOneById("Theme", $request->request->get("theme"));
        $questionToEdit = $this->findOneById("Question",$request->request->get("question_id"));

        $em = $this->getManager();
        
        if( $texteQuestion == null || $themeQuestion == null || $vrais == null || $reponses == null)
            return $this->redirectWithErrorFlash("create_question", "Question invalide");

        if( count($reponses) < 2)
            return $this->redirectWithErrorFlash("create_question", "Veuillez saisir au moins 2 réponses");
        
        if($questionToEdit != null && $questionToEdit->isEditable())
            $em->remove($questionToEdit);
        
        $question = new Question($texteQuestion, $themeQuestion, $this->getUser());
    
        if(!$this->addReponses($question, $vrais, $reponses))
            return $this->redirectWithErrorFlash("create_question", "Il faut au moins une réponse vraie");
        
        $em->persist($question);
        $em->flush();
        return $this->redirectWithSuccessFlash("questions", "Ajout effectué avec succès");
    }
    
    /**
     * Traitements des réponses pour les ajouter à une question $question en cours de création.
     * 
     * @param Question $question
     * @param type $vrais tableau comportant une entrée par bonne réponse
     * @param type $reponses tableau des réponses
     * @return boolean true si la question a au moins une réponse vrai (nécessaire pour validation)
     */
    public function addReponses(Question $question, $vrais, $reponses){
        $auMoins1Vrai = false;
        foreach($reponses as $key => $texteReponse)
            if($texteReponse != null){
                $vraiReponse = array_key_exists($key,$vrais); 
                $auMoins1Vrai = $auMoins1Vrai || $vraiReponse;
                
                $question->addReponse(new Reponse($texteReponse, $vraiReponse, $question));
            }
            
        return $auMoins1Vrai;
    }
}
