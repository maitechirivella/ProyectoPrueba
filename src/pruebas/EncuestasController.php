<?php

class EncuestasController extends ControllerBase
{
	public function initialize() {
		$this->tag->setTitle("Encuestas");
	}
	public function beforeExecuteRoute($dispatcher) {
		// Only logged users can access functionalities of this controller
		if($this->session->has("usuario")) {
			$this->usuario = unserialize($this->session->get("usuario"));
			if($this->usuario->foto == "") {
				$this->usuario->foto = $this->config->application->fotoAnonimo;
			}
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
		$this->tag->prependTitle("Index | ");
		$this->view->setVar("activas",Encuesta::find(array(
                    "fecha_fin >= BD2fecha(time())" ,
                    "order" => "fecha_fin ASC"
                )));

    }
	
	public function addAction()
    {

    }
	
	public function submitAction()
    {

    }
}

