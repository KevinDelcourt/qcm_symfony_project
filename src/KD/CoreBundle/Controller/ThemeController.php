<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\Theme;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gère l'affichage/ajout/supression des thèmes
 */
class ThemeController extends BaseController
{
    /**
     * Affiche la liste des thèmes
     */
    public function indexAction()
    {
        $listThemes = $this->getRepository('Theme')->allThemesWithQuestionCount();
        return $this->render('KDCoreBundle:Themes:index.html.twig', array('listThemes' => $listThemes));
    }
    
    /**
     * Ajoute un thème à la base de données si il n'est pas déjà utilisé.
     * 
     * @param Request $request
     */
    public function addThemeAction(Request $request)
    {
        $nom = $request->request->get('nom');
        
        if($nom == null)
            return $this->redirectWithErrorFlash("themes", "Veuillez renseigner un thème");

        if( $this->getRepository('Theme')->findOneByNom($nom) != null )
            return $this->redirectWithErrorFlash("themes", "Il y a déjà un thème $nom");
         
        $theme = new Theme($nom);
        
        $this->persistFlush($theme);
        return $this->redirectWithSuccessFlash("themes","Le thème $nom a bien été ajouté");
    }
    
    /**
     * Modifie le nom d'un thème après vérification
     * 
     * @param Request $request
     */
    public function editThemeAction(Request $request)
    {
        $nom = $request->request->get('nom');
        
        $repositoryTheme = $this->getRepository("Theme");
        $theme = $repositoryTheme->findOneById($request->request->get('id'));
        
        if($nom == null || $theme == null)
            return $this->redirectWithErrorFlash("themes", "Erreur");

        if( $repositoryTheme->findOneByNom($nom) != null )
            return $this->redirectWithErrorFlash("themes", "Il y a déjà un thème $nom");
         
        $theme->setNom($nom);
        
        $this->persistFlush($theme);
        return $this->redirectWithSuccessFlash("themes","Le thème $nom a bien été modifié");
    }
    
    /**
     * Supprime le thème $id si il n'a pas de questions associées.
     */
    public function deleteThemeAction($id)
    {
        $theme = $this->findOneById("Theme",$id);
        
        if($theme == null)
            return $this->redirectWithErrorFlash("themes", "Adresse incorrecte");

        if($theme->getQuestionCount() > 0)
            return $this->redirectWithErrorFlash("themes", "Action impossible: ce thème comporte des questions");
        
        $this->removeFlush($theme);
        return $this->redirectWithSuccessFlash("themes","Le thème a bien été supprimé");
    }
}
