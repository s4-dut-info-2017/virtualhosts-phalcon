<?php
class AuthController extends ControllerBase {

	public function initialize(){
		if($this->request->isAjax()){
			$this->view->disable();
		}
	}

	public function indexAction(){
		echo Auth::getInfoUser($this);
		echo $this->jquery->compile($this->view);
	}

	/**
	 * Déconnecte l'utilisateur actuel
	 */
	public function disconnectAction(){
		$this->session->remove("activeUser");
		$this->session->destroy();
		$this->dispatcher->forward(["controller"=>"Auth","action"=>"index"]);
	}

	/**
	 * Simule une connexion du premier utilisateur trouvé dans la BDD
	 */
	public function connectAsUserAction(){
		$user=User::findFirst();
		$this->session->set("activeUser",$user);
		$this->dispatcher->forward(["controller"=>"Auth","action"=>"index"]);
	}

	public function pleaseLoginAction(){
		$message=$this->semantic->htmlMessage("error","Merci de vous connecter pour tester.");
		$message->setIcon("announcement")->setError();
		$message->setDismissable();
		$message->addContent(Auth::getInfoUser($this,"-login"));
		echo $message;
		echo $this->jquery->compile($this->view);
	}


}