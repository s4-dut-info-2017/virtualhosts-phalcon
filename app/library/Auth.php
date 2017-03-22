<?php

/**
 * Classe de gestion de l'authentification
 * @author jcheron
 * @version 1.1
 * @package cloud.my
 */
class Auth {
	/**
	 * Retourne l'utilisateur actuellement connecté<br>
	 * ou NULL si personne ne l'est
	 * @return User
	 */
	public static function getUser($controller){
		$session=$controller->session;
		$user=null;
		if($session->has("activeUser"))
			$user=$session->get("activeUser");
		return $user;
	}

	/**
	 * Retourne vrai si un utilisateur est connecté
	 * @return boolean
	 */
	public static function isAuth($controller){
		return null!==self::getUser($controller);
	}

	/**
	 * Retourne vrai si un utilisateur de type administrateur est connecté<br>
	 * Faux si l'utilisateur connecté n'est pas admin ou si personne n'est connecté
	 * @return boolean
	 */
	public static function isAdmin($controller){
		$user=self::getUser($controller);
		if($user instanceof User){
			return $user->getIdrole()!==2;
		}else{
			return false;
		}
	}

	/**
	 * Retourne la zone d'information au format HTML affichant l'utilisateur connecté<br>
	 * ou les boutons de connexion si personne n'est connecté
	 * @return string
	 */
	public static function getInfoUser(ControllerBase $controller,$inc=""){
		$jquery=$controller->jquery;
		$user=self::getUser($controller);
		if(isset($user)){
			$bt=$jquery->semantic()->htmlButton("btDisconnect".$inc,"Déconnexion","basic green");
			$bt->addLabel($user."",false,"user");
			$bt->getOnClick("Auth/disconnect","#divInfoUser",["jsCallback"=>$controller->jquery->getDeferred("index/index","#content-container")]);
		}else{
			$bt=$jquery->semantic()->htmlButton("btConnect".$inc,"Connexion pour tests");
			$bt->addIcon("sign in");
			$bt->getOnClick("Auth/connectAsUser","#divInfoUser",["jsCallback"=>$controller->jquery->getDeferred("index/index","#content-container")]);
		}
		return $bt;
	}
}