<?php

namespace KD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * Page d'acceuil
     * Redirection en fonction des droits accordés
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROF')) 
            return $this->render('KDCoreBundle:Default:prof.html.twig');
        elseif($this->get('security.authorization_checker')->isGranted('ROLE_ELEVE'))
            return $this->redirectToRoute("liste_qcms_eleve");
        else
            return $this->redirectToRoute("login");
    }
    
    /**
     * Page des mentions légales
     */
    public function mentionsAction()
    {
        return $this->render('KDCoreBundle:Default:mentions_legales.html.twig');
    }
}
