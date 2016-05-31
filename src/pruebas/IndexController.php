<?php

class IndexController extends ControllerBase
{
	public function initialize() {
		$this->tag->setTitle("Index");
	}
    public function indexAction()
    {   $this->tag->prependTitle("Index | ");
		/* $data=new DateUtils();
        $d2=$data->BD2fecha("2016-05-10 22:59");
        echo "Fecha formato: ".$d2; */
        if(!$this->session->has('usuario')) {
		// Redireccionamos al login. El usuario no ha iniciado sesión
		$this->dispatcher->forward(array(
			'action' => 'login'
		));

    }
	else //sesión iniciada, redirigimos a encuestas
		$this->dispatcher->forward(array(
		'controller' => 'encuestas',
		'action' => 'index'
	));
	}

	public function show404Action() {
		// Enviamos la cabecera con la respuesta 404. Contenido en show404.volt
		$this->response->setStatusCode(404, "Not Found");
	}

	public function loginAction() {
		$this->tag->prependTitle("Login | ");
		//echo "Estoy en el controlador 'index', acción 'login'";
	}
	
	public function submitLoginAction() {
		if($this->request->isPost()) {
			$pass = hash("sha256",$this->request->getPost("password"));
			$email = $this->request->getPost("email");
		}
		$usuaBD = Usuario::findFirstByCorreo($email);
                
		if ($usuaBD)
                    { if ($usuaBD->getPassword()== $pass) 
				{
					$this->session->usuario = $usuaBD->getNombre();
					$this->dispatcher->forward(array(
						'action' => 'index'));
				}
                    
			}
		else
			{
                        $this->view->setVar("err", "Usuario no válido");
                        $this->dispatcher->forward(array(
			'action' => 'index'));
			}
		/*login correcto...
		$this->session->usuario = Usuario::findFirst();
                $this->tag->prependTitle("submitLogin | ");
                //echo "tiene usuario: " . $this->session->has("usuario");
                //echo "Usuario: ".$this->session->usuario->nombre; */
               
	}

	public function logoutAction() {
		//borramos usuario y destruimos sesión
		$this->session->remove("usuario");
		$this->session->destroy();
		//redirigimos al login
		$this->dispatcher->forward(array(
			'action' => 'login'
		));
	}

	public function registroAction() {
		$this->tag->prependTitle("Registro | ");
		//echo "Registro...'";
	}
	
	public function submitRegistroAction() {
		$this->dispatcher->forward(array(
			'action' => 'registro'
		));
	
	}

	//pruebas ejercicio 3
	public function pruebasAction() {
        $this->tag->prependTitle("Pruebas | ");
        
        echo "<p>TEMA 3 - José Luis Aznar</p>";
        echo "<p>Consulta 1: nombres y correos de usuario:</p>";
		$usuarios = Usuario::find();
		echo "En total hay " . count($usuarios) . ":", "<br />";
		foreach ($usuarios as $usuario) {
			echo $usuario->getNombre()." ".$usuario->getCorreo(), "<br />";
			}
	echo "<hr /> ";
        
        echo "<p>Consulta 2: num. de encuestas:</p>";
        $encuestas = Encuesta::find();
        echo "En total hay: " . count($encuestas) . " encuestas.", "<br />";
        echo "<hr /> ";

	echo "<p> Consulta 3: Num. de opciones por encuesta </p>";
        $numOpciones = Opcion::count(
                        array(
                            'group' => 'encuesta',
                            'order' => 'rowcount'
                        )
        );
        foreach ($numOpciones as $num) {
            $encuesta = Encuesta::findFirst('id=' . $num->encuesta);
            echo "La encuesta <b>".$encuesta->getDescripcion()."</b> tiene " . $num->rowcount . " opciones 					","<br />";
        }
        echo "<hr /> ";

	echo "<p>Consulta 4: Primer usuario con modelo hidratación</p>";
        $usuarios = Usuario::find();
        $usuarios->setHydrateMode(Phalcon\Mvc\Model\Resultset::HYDRATE_ARRAYS);
	
        echo $usuarios[0][nombre] . "</br>";
	echo $usuarios[0][correo] . "</br>";
        echo $usuarios[0][password] . "</br>";
        echo "<hr /> ";

	echo "<p>Consulta 5: mensajes de error al crear encuesta</p>";
        $encuesta = new Encuesta();
        $encuesta->setDescripcion("Encuesta con error");
        $encuesta->setCreador("100");

        if (!$encuesta->save()) {
			echo "La encuesta no se ha podido crear", "<br />";
            foreach ($encuesta->getMessages() as $message) {
                echo "Mensaje: ",$message->getMessage(), "<br />";
				echo "Campo: ", $message->getField(), "<br />";
				echo "Tipo: ", $message->getType(), "<br />";
            }
        } else {
            echo "La encuesta ha sido creada con éxito";
        }
        echo "<hr /> ";
		
		echo "<p>Consulta 6: mensajes de error en voto</p>";
        $voto = new Voto();
        $voto->setUsuario("100");
        $voto->setEncuesta("1");
        $voto->setOpcion("50");

        if (!$voto->save()) {
            echo "No se ha podido crear el voto", "<br />";
            foreach ($voto->getMessages() as $message) {
                echo "Mensaje: ".$message->getMessage()." Campo: ", $message->getField()." Tipo: ". $message->getType(), "<br />";
            }
        } else {
            echo "El voto ha sido creado con éxito";
        }
        echo "<hr /> ";

	echo "<p>Consulta 7: Usuarios y encuestas</p>";
        $usuarios = Usuario::find();
        foreach ($usuarios as $usuario) {
            echo "<p><h3>" . $usuario->getNombre() . "</h3></p>";
            foreach ($usuario->Encuestas as $enc) {
                echo "<p><h4>" . $enc->getDescripcion() ."</h4></p>";
                foreach ($enc->Opciones as $opc) {
                    echo "<p><h5>- " . $opc->getTexto() . "</h4></p>";  
                }
            }
        }
    }	
}

