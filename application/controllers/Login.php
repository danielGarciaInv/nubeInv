<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('datosUsuario');
	}

	public function index()
	{
	    $this->validarTmpUsrs();
		if($this->session->userdata('correo')){
			redirect('Dashboard');
		}
		$info['titulo']="Iniciar Sesión";
		$this->load->view('Login',$info);
	}
	
	public function validarTmpUsrs(){
	    $usrsTmp = $this->datosUsuario->consultaUsuariosTmp();
		foreach($usrsTmp as $usrTmp){
		    if(date_create(date("Y-m-d H:i:s")) > date_create($usrTmp->caducidad)){
		        $this->datosUsuario->eliminarUsuario($usrTmp->idusuario);
		        if($this->session->userdata('correo') == $usrTmp->correo){
		            $this->cerrarSesion();
		        }
		    }
		}
	}
	
	public function iniciarSesion(){
		$correo = $this->input->post('correo');
		$pwd = md5($this->input->post('pwd'));
		$resultado = $this->datosUsuario->validarUsr($correo, $pwd);
		$datosUsr = $resultado->result();
		
		if($resultado->num_rows()>0){
			$resultadoDos = $this->datosUsuario->permisos($datosUsr[0]->id_rol);
			$permisosQuery = $resultadoDos->result_id;
			$permisos = [];
			while($perm = mysqli_fetch_array($permisosQuery)){
				array_push($permisos, $perm['id_cat']);
			}
			//var_dump($permisos);
			$this->session->set_userdata('correo',$correo);
			$this->session->set_userdata('rol',$datosUsr[0]->id_rol);
			$this->session->set_userdata('subir',$datosUsr[0]->subir); # ++++++++++++++++++++++++++++++++++++
			$this->session->set_userdata('eliminar',$datosUsr[0]->eliminar); # ++++++++++++++++++++++++++++++++++++
			$this->session->set_userdata('permisos',$permisos);
			$this->session->set_userdata('nombre',$datosUsr[0]->nombre);
			$this->session->set_userdata('dirActual','cargados/');

			redirect('Dashboard');
		}else{
			$info['errores']="El usuario o la contraseña son incorrectos, verifica los datos e intenta de nuevo";
			$info['titulo']="Iniciar Sesión";
			$this->load->view('Login',$info);
		}
	}
	public function cerrarSesion(){
		$this->session->sess_destroy();
		redirect('Login');
	}
	public function registrarse($id){
		if($id == "1"){
			echo("Violación de protocolo, su dirección IP será proporcionada a las autoridades correspondientes en su pais.");
		}else{
			$this->validarTmpUsrs();
			$info['id'] = $id;
			$info['cont']="Configure su nueva contraseña";
			$info['titulo']="Terminar Registro";
			$this->load->view('Registrarse',$info);
		}
	}
	public function actualizarContrasena($id){
		$pwd = md5($this->input->post('pwd'));
		$pwdc = md5($this->input->post('pwdc'));
		if($pwd == $pwdc){
        	$this->datosUsuario->cambiarContrasena($pwd, $id);
        	redirect('Login');
		}else{
		    $info['id'] = $id;
        	$info['cont']="Configure su nueva contraseña";
        	$info['titulo']="Terminar Registro";
        	$info['errores']="Las contraseñas no coinciden, intente de nuevo";
        	$this->load->view('Registrarse',$info);
		}
	}

	public function recuperarContrasena(){
		$this->validarTmpUsrs();

		$fecha = date('Y-m-d');
		$cad = strtotime('+1 day', strtotime($fecha));
		$cad = date('Y-m-d', $cad);
		$cad .= date(' H:i:s');
		
		$info['cont']="Ingrese su correo electrónico registrado";
		$info['titulo']="Recuperar su contraseña";
		$info['caducidad']=$cad;
		$this->load->view('accion/recuperarContrasena',$info);
	}

	public function nuevaContrasena($id){
		$this->validarTmpUsrs();
		$info = $this->datosUsuario->consultarCambioPwd($id);
		if((count($info) > 0) && (date('Y-m-d H:i:s') < $info[0]->caducidad)){
			$info['id'] = $info[0]->idusuario;
			$info['cont']="Configure su nueva contraseña";
			$info['titulo']="Recuperar Contraseña";
			$this->load->view('accion/actualizarContrasena',$info);
		}else{
			$this->datosUsuario->eliminarCambioPwd($id);
			$info['cont']="Este enlace ha caducado, por favor solicite un nuevo cambio de contraseña";
			$info['titulo']="Enlace Caducado";
			$this->load->view('accion/actualizarContrasena',$info);
		}
	}

	public function enviarCorreoRecuperacion(){
		$correo = $this->input->post('correo');
		$resultado = $this->datosUsuario->consultaUsuario($correo);

		if($resultado->num_rows()>0){
    		$resultado = $resultado->result();
    		$idCambio = $this->datosUsuario->insertarCambioPwd($resultado[0]->idusuario, $this->input->post('caducidad'));
    		$idCambio = $idCambio->result();

			$mensaje = '<h2>Has solicitado la restauración de tu contraseña en InvirtualCloud.</h2><br>';
			$mensaje .= '<p>Da click en el siguiente enlace para configurar una nueva contraseña</p><br>';
			$mensaje .= '<a href="'.base_url('index.php/Login/nuevaContrasena/').$idCambio[0]->maxid.'" ';
			$mensaje .= 'style="padding: 10px; color:white; background: #159eee; border-radius: 10px; text-decoration: none;"';
			$mensaje .= '>Recuperar Contraseña</a>';

			$this->load->library('email');

			$this->email->set_mailtype('html');
			$this->email->from('soporte@invirtual.mx', 'Admin Invirtual');
			$this->email->to($resultado[0]->correo);
			$this->email->subject('Recuperar tu contraseña de usuario Invirtual');
			$this->email->message($mensaje);

			if($this->email->send()){
				echo 'true';
			}else{
				echo 'false';
			}
		}

		$info['cont']="Ingrese su correo electrónico registrado";
		$info['titulo']="Recuperar su contraseña";
		$info['info']="Si exste algun usuario registrado con esta dirección le enviaremos un mensaje de recuperación.<br>Si su usuario es temporal considere su tiempo de caducidad.";
		$this->load->view('accion/recuperarContrasena',$info);
	}
}
