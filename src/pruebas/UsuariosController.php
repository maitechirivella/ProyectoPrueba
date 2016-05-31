<?php

class UsuariosController extends ControllerBase
{

	public function beforeExecuteRoute($dispatcher) {
		// Only logged users can access functionalities of this controller
		if($this->session->has("usuario")) {
			}
		else {
			if($this->dispatcher->getActionName() !== "show") {
				$this->dispatcher->forward(array(
					"controller" => "index",
					"action" => "index"));
			return false;
		}
		$this->usuario = Usuario::createGuest();
		}
	}
    
	public function indexAction()
    {
		$this->dispatcher->forward(array(
			'action' => 'perfil'));
    }

	public function perfilAction()
    {
		
    }	

	public function updatePerfilAction()
    {
		$this->dispatcher->forward(array(
			'action' => 'perfil'));
    }

}

