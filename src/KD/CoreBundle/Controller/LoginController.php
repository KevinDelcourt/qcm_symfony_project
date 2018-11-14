<?php

namespace KD\CoreBundle\Controller;

use KD\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends BaseController
{
    /**
     * Affichage de la page de login
     * 
     * @param Request $request nécessaire pour le composant Security de Symfony
     */
    public function indexAction(Request $request)
    {
        return $this->render('KDCoreBundle:Default:login.html.twig');
    }
    
    /**
     * Création d'un utilisateur
     * 
     * @param UserPasswordEncoderInterface $encoder
     * @param Request $request
     * @return type
     */
    public function addUserAction(UserPasswordEncoderInterface $encoder, Request $request)
    {
        $username =  $request->request->get('username');
        $mdp =  $request->request->get('mdp');
        $email =  $request->request->get('email');
        $role =  $request->request->get('role');
        
        if( $username == null || $mdp == null || $email == null || $role == null )
            return $this->redirectWithErrorFlash("login", "Veuillez renseigner tout les champs");
        
        if($this->getRepository("User")->findOneByUsername($username) != null)
            return $this->redirectWithErrorFlash("login", "Cet utilisateur existe déjà");
        
        $role = "ROLE_".strtoupper($role);
        
        $user = new User($username,$email,$role);
        
        $mdpEncoded = $encoder->encodePassword($user, $mdp);
        
        $user->setPassword($mdpEncoded);

        $this->persistFlush($user); 
        return $this->redirectWithSuccessFlash("login", "L'utilisateur a bien été créé");
    }
}
