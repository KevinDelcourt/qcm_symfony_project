<?php

namespace KD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Classe de controleur utilitaire pour faciliter certaines écritures.
 * Contient des fonctions utilisées par tout les controleurs de l'application.
 * Tout les controleurs héritent de cette classe.
 */
class BaseController extends Controller
{
    /**
     * Redige vers $route en créant un message d'erreur $message
     * 
     * @param String $route
     * @param String $message
     */
    protected function redirectWithErrorFlash($route,$message)
    {
        $this->addFlash('danger',$message);
        return $this->redirectToRoute($route);
    }
    
    /**
     * Redirige vers $route en créant un message de succès $message.
     * 
     * @param String $route
     * @param String $message
     */
    protected function redirectWithSuccessFlash($route,$message)
    {
        $this->addFlash('success',$message);
        return $this->redirectToRoute($route);
    }
    
    /**
     * Raccourci de l'obtention d'un répertoire
     * 
     * @param String $entity_class
     * @return Repository
     */
    protected function getRepository($entity_class)
    {
        return $this->getDoctrine()->getRepository("KDCoreBundle:$entity_class");
    }
    
    /**
     * Raccourci de l'obtention de l'entityManager
     * 
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }
    
    /**
     * Raccourci de la persistance d'une entité.
     * A n'utiliser que lorsqu'on a une entité à traiter dans une action.
     * 
     * @param type $entity
     */
    protected function persistFlush($entity)
    {
        $em = $this->getManager();
        $em->persist($entity);
        $em->flush();
    }
    
    /**
     * Raccourci de la suppression d'ne entité.
     * A n'utiliser que lorsqu'on a une entité à traiter dans une action.
     * 
     * @param type $entity
     */
    protected function removeFlush($entity)
    {
        $em = $this->getManager();
        $em->remove($entity);
        $em->flush();
    }
    
    /**
     * Raccourci de l'obtention d'une entité de classe $entity_class par son $id
     * 
     * @param String $entity_class
     * @param type $id
     * @return entity_class
     */
    protected function findOneById($entity_class,$id)
    {
        return $this->getRepository($entity_class)->findOneById($id);
    }
}
