<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Controlador Dashboard encargado de desplegar el panel principal una vez iniciada la sesión, ademas contiene
las principales funciones para los archivos.
*/
class Dashboard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('DashboardDB');
        $this->load->model('DatosUsuario');
    }

	public function index(){
		$this->validarTmpUsrs();

        if($this->session->userdata('correo')){
            $cont['archivos'] = $this->mostrarArchivos();
            $cont['folders'] = $this->escanearFolders("cargados/");
            if($this->session->userdata('rol') === '1'){
                $cont['roles'] = $this->consultaRoles();
            }
            
            $cont['categorias'] = $this->mostrarCategorias();
            $cont['usuarios'] = $this->mostrarUsuarios();
            $cont['tituloPagina'] = 'Mi Dashboard';
            $this->session->set_userdata('dirActual','cargados/');
            $this->load->view('comun/head',$cont);
            $this->load->view('Dashboard',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
	}

    // Función para validar si los usuarios temporales ya han caducado para eliminarlos, se ejecuta en casi todos los metodos de renderizado xd
	public function validarTmpUsrs(){
	    $usrsTmp = $this->DatosUsuario->consultaUsuariosTmp();
		foreach($usrsTmp as $usrTmp){
		    if(date_create(date("Y-m-d H:i:s")) > date_create($usrTmp->caducidad)){
		        $this->DatosUsuario->eliminarUsuario($usrTmp->idusuario);
		        if($this->session->userdata('correo') == $usrTmp->correo){
		            redirect('Login/cerrarSesion');
		        }
		    }
		}
	}
	
	// Función que devuelve las categorías registradas en BD
    public function mostrarCategorias(){
        $categorias = [];
        $categoriasPrev = $this->DashboardDB->consultaCategorias();
        while($filaCat = mysqli_fetch_array($categoriasPrev)){
            array_push($categorias,$filaCat);
        }
        return $categorias;
    }

    public function mostrarUsuarios(){
        $usrsArr = [];
        $usuariosPrev = $this->DatosUsuario->consultaUsuarios();
        while($filaUsr = mysqli_fetch_array($usuariosPrev)){
            array_push($usrsArr,$filaUsr);
        }
        return $usuariosPrev;
    }

/*
 ---------------------------------------------- Función subir archivo
*/
    public function subirArchivo(){
        $directorio = $this->session->userdata('dirActual');

        $categoria = $_POST['categoria'];
        $checkNotificar = $_POST['checkNotificar'];
        $checksCorreos = json_decode($_POST['checksCorreosArr']);
        $charReservados = [" ","!","#","$","%","&","'","(",")","*","+",",","/",":",";","=","?","@","[","]"];
        foreach ($_FILES as $archivo) {
            $nombre = $archivo['name'];
            $extencion = substr($nombre,strrpos($nombre,".")+1);
            $nombre = substr($nombre,0,strrpos($nombre,"."));

            foreach ($charReservados as $character) {
                $nombre = str_replace($character,"-",$nombre);
            }
            while(file_exists($directorio.$nombre.'.'.$extencion)){
                $nombre .= '1';
            }

            $tamanoKb = $archivo['size']*0.001;
            $fecha = date("d/m/Y");
            $tipoStr = $archivo['type'];
            $tipoStr = substr($tipoStr,0,strpos($tipoStr,"/"));

            $archivosExistentes = [];
            $consultaRap = $this->DashboardDB->devolverArchivos();
            while($field = mysqli_fetch_array($consultaRap)){
                array_unshift($archivosExistentes,$field);
            }
            $indice = strval(($archivosExistentes[count($archivosExistentes)-1]['id'])+1);

            // Asignación de un numero en función de su tipo y su extención (Esto es para visualizar los iconos o las miniaturas)
            switch($tipoStr){
                case 'image':
                    if($extencion == 'svg'||$extencion == 'ico'){
                        $tipo = 11;
                    }else{
                        $tipo = 1;
                    }
                    $cat = 'img';
                    // $nombre = 'Invirtual_image_'.$indice;
                break;
                // Tipo video: nada que reportar xd
                case 'video':
                    $tipo = 2;
                    $cat = 'aud';
                    // $nombre = 'Invirtual_video_'.$indice;
                break;
                // Tipo audio: nada que reportar
                case 'audio':
                    $tipo = 3;
                    $cat = 'aud';
                    // $nombre = 'Invirtual_audio_'.$indice;
                break;
                // Tipo application: los candidatos a tener un icono especial de momento son pdf, archivos de office, sql y zip, los demas tendran un icono de archivo
                case 'application':
                    switch ($extencion) {
                        case 'pdf':
                            // El tipo 41 tambien tendrá un enlace en su renderizado
                            $tipo = 41;
                            // $nombre = 'Invirtual_pdf_'.$indice;
                            break;
                        case 'docx':
                            $tipo = 42;
                            // $nombre = 'Invirtual_doc_'.$indice;
                            break;
                        case 'pptx':
                            $tipo = 43;
                            // $nombre = 'Invirtual_slide_'.$indice;
                            break;
                        case 'xlsx':
                            $tipo = 44;
                            // $nombre = 'Invirtual_sheet_'.$indice;
                            break;
                        case 'sql':
                            $tipo = 45;
                            // $nombre = 'Invirtual_db_'.$indice;
                            break;
                        case 'zip':
                        case 'rar':
                            $tipo = 46;
                            // $nombre = 'Invirtual_file_'.$indice;
                            break;
                        default:
                            $tipo = 6;
                            // $nombre = 'Invirtual_file_'.$indice;
                            break;
                    }
                    $cat = 'appl';
                break;
                // Tipo text: tendrá un icono de archivo de texto
                case 'text':
                    $tipo = 5;
                    $cat = 'appl';
                    // $nombre = 'Invirtual_text_'.$indice;
                break;
                // Demas: cualquier otro tipo que no haya sido asignado tendra un icono de archivo
                default :
                    $tipo = 6;
                    $cat = 'appl';
                    // $nombre = 'Invirtual_file_'.$indice;
            }
            $nombre .= '.'.$extencion;
            $ruta = $directorio . $nombre;

            var_dump($nombre);
            var_dump($ruta);

            // Condicional para desplegar el tamaño en KB, en MB o en GB(ésta ya es la variable definitiva que se enviara a BD)
            if($tamanoKb>1000000.0){
                $tamanoKb /= 1000000.0;
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' GB';
            }else if($tamanoKb>1024.0){
                $tamanoKb /= 1024.0;
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' MB';
            }else{
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' KB';
            }

            // Ya listas todas las variables, toca mover el archivo al directorio final y registrarlo en BD
            if(move_uploaded_file($archivo['tmp_name'],$ruta)) {
                // Si y solo si el archivo se movió, éste se registrá en BD
                $this->DashboardDB->registroArchivo($nombre,$ruta,$tamano,$fecha,$tipo,$cat,$categoria);
                echo "Archivo Subido";
            }else{
                echo "Error al subir archivo";
            }
        }

        // Una vez terminados de subir los archivos se envia un correo a los usuarios sobre el nuevo contenido, si el check de notificación esta activado
        // Primero se hace una consulta para determinar la categoria del ultimo archivo subido (Esto para que no haya que enviar muchos correos)
        
        if($checkNotificar === 'true'){
            $cat = $this->DashboardDB->ultimaCategoria();
            $mensaje = '<h2>Nuevo contenido disponible</h2><br>';
            $mensaje .= '<p>Se ha subido nuevo contenido a la sección '.$cat[0]->descripcion.'</p><br>';
            $mensaje .= '<a href="'.base_url().'" ';
            $mensaje .= 'style="padding: 10px; color:white; background: #159eee; border-radius: 10px; text-decoration: none;"';
            $mensaje .= '>Ir Al Dashboard</a>';
    
    
            $this->load->library('email');
    
            // Antes de enviar, se hace una consulta de cuales usuarios tienen permisos de lectura sobre la ultima categoría
            // $usrsCorreo = $this->DashboardDB->usuariosCorreo($cat[0]->id_cat);
            foreach($checksCorreos as $correo){
                $this->email->set_mailtype('html');
                $this->email->from('soporte@invirtual.mx', 'Admin Invirtual');
                $this->email->subject('Nuevo contenido disponible en Invirtual');
                $this->email->message($mensaje);
                $this->email->to($correo);
                if($this->email->send()){
                    echo "Correo Enviado";
                }else{
                    echo "Error de envío";
                }
            }
        }
    }

    public function subirCarpeta(){
        $directorio = $this->session->userdata('dirActual');
        
        $categoria = $_POST['categoria'];
        $checkNotificar = $_POST['checkNotificar'];
        $checksCorreos = json_decode($_POST['checksCorreosArr']);
        $charReservados = [" ","!","#","$","%","&","'","(",")","*","+",",","/",":",";","=","?","@","[","]"];

        for($i = 0; $i < count($_POST['folder']); $i++){
            $folder = dirname($_POST['folder'][$i]);
            
            if(strrpos($folder,'/') > 0){
                $nombreFolder = substr($folder,strrpos($folder,'/') + 1);
            }else{
                $nombreFolder = $folder;
            }
            $path = $directorio . $folder;
            
            if(!file_exists($path)){
                mkdir($path, 0775, true);
                // -------------------------------------------------------- Funcion para registrar carpeta en BD
                $this->DashboardDB->registrarCarpeta($nombreFolder, $path);
            }

            $temp_file = $_FILES['file']['tmp_name'][$i];
            $nombre = $_FILES['file']['name'][$i];
            $extencion = substr($nombre,strrpos($nombre,".")+1);
            $nombre = substr($nombre,0,strrpos($nombre,"."));


            foreach ($charReservados as $character) {
                $nombre = str_replace($character,"-",$nombre);
            }
            while(file_exists($path.$nombre.'.'.$extencion)){
                $nombre .= '1';
            }

            $tamanoKb = $_FILES['file']['size'][$i] * 0.001;
            $fecha = date("d/m/Y");
            $tipoStr = $_FILES['file']['type'][$i];
            $tipoStr = substr($tipoStr,0,strpos($tipoStr,"/"));

            
            $consultaRap = $this->DashboardDB->ultimoIdArchivos();
            $indice = $consultaRap[0]->indice + 1;

            // Asignación de un numero en función de su tipo y su extención (Esto es para visualizar los iconos o las miniaturas)
            switch($tipoStr){
                // Tipo imagen: descartados svg e ico para crear thumb, las demas extenciones tendran su miniatura
                case 'image':
                    if($extencion == 'svg'||$extencion == 'ico'){
                        $tipo = 11;
                    }else{
                        $tipo = 1;
                    }
                    $cat = 'img';
                    // $nombre = 'Invirtual_image_'.$indice;
                break;
                // Tipo video: nada que reportar xd
                case 'video':
                    $tipo = 2;
                    $cat = 'aud';
                    // $nombre = 'Invirtual_video_'.$indice;
                break;
                // Tipo audio: nada que reportar
                case 'audio':
                    $tipo = 3;
                    $cat = 'aud';
                    // $nombre = 'Invirtual_audio_'.$indice;
                break;
                // Tipo application: los candidatos a tener un icono especial de momento son pdf, archivos de office, sql y zip, los demas tendran un icono de archivo
                case 'application':
                    switch ($extencion) {
                        case 'pdf':
                            // El tipo 41 tambien tendrá un enlace en su renderizado
                            $tipo = 41;
                            // $nombre = 'Invirtual_pdf_'.$indice;
                            break;
                        case 'docx':
                            $tipo = 42;
                            // $nombre = 'Invirtual_doc_'.$indice;
                            break;
                        case 'pptx':
                            $tipo = 43;
                            // $nombre = 'Invirtual_slide_'.$indice;
                            break;
                        case 'xlsx':
                            $tipo = 44;
                            // $nombre = 'Invirtual_sheet_'.$indice;
                            break;
                        case 'sql':
                            $tipo = 45;
                            // $nombre = 'Invirtual_db_'.$indice;
                            break;
                        case 'zip':
                        case 'rar':
                            $tipo = 46;
                            // $nombre = 'Invirtual_file_'.$indice;
                            break;
                        default:
                            $tipo = 6;
                            // $nombre = 'Invirtual_file_'.$indice;
                            break;
                    }
                    $cat = 'appl';
                break;
                // Tipo text: tendrá un icono de archivo de texto
                case 'text':
                    $tipo = 5;
                    $cat = 'appl';
                    // $nombre = 'Invirtual_text_'.$indice;
                break;
                // Demas: cualquier otro tipo que no haya sido asignado tendra un icono de archivo
                default :
                    $tipo = 6;
                    $cat = 'appl';
                    // $nombre = 'Invirtual_file_'.$indice;
            }
            $nombre .= '.'.$extencion;
            $ruta = $path . '/' . $nombre;
            
            // Condicional para desplegar el tamaño en KB, en MB o en GB(ésta ya es la variable definitiva que se enviara a BD)
            if($tamanoKb>1000000.0){
                $tamanoKb /= 1000000.0;
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' GB';
            }else if($tamanoKb>1024.0){
                $tamanoKb /= 1024.0;
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' MB';
            }else{
                $tamano = number_format($tamanoKb,2);
                $tamano .= ' KB';
            }

            // Ya listas todas las variables, toca mover el archivo al directorio final y registrarlo en BD
            if(move_uploaded_file($temp_file,$ruta)) {
                // Si y solo si el archivo se movió, éste se registrá en BD
                $this->DashboardDB->registroArchivo($nombre,$ruta,$tamano,$fecha,$tipo,$cat,$categoria);
                echo "Archivo Subido";
            }else{
                echo "Error al subir archivo";
            }
        }

        if($checkNotificar === 'true'){
            $cat = $this->DashboardDB->ultimaCategoria();
            $mensaje = '<h2>Nuevo contenido disponible</h2><br>';
            $mensaje .= '<p>Se ha subido nuevo contenido a la sección '.$cat[0]->descripcion.'</p><br>';
            $mensaje .= '<a href="'.base_url().'" ';
            $mensaje .= 'style="padding: 10px; color:white; background: #159eee; border-radius: 10px; text-decoration: none;"';
            $mensaje .= '>Ir Al Dashboard</a>';
    
    
            $this->load->library('email');
    
            // Antes de enviar, se hace una consulta de cuales usuarios tienen permisos de lectura sobre la ultima categoría
            foreach($checksCorreos as $correo){
                $this->email->set_mailtype('html');
                $this->email->from('soporte@invirtual.mx', 'Admin Invirtual');
                $this->email->subject('Nuevo contenido disponible en Invirtual');
                $this->email->message($mensaje);
                $this->email->to($correo);
                if($this->email->send()){
                    echo "Correo Enviado";
                }else{
                    echo "Error de envío";
                }
            }
        }
    }

/*
 ---------------------------------------------- Función crearThumb
 Función para crear las miniaturas de las imagenes con la libreria image_lib de CI
*/
    function crearThumb($filename){
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = 'cargados/'.$filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = 'cargados/thumbs/';
        $config['thumb_marker'] = '';
        $config['width'] = 160;
        $config['height'] = 160;

        $this->image_lib->initialize($config);
        if(!$this->image_lib->resize()){
            echo $this->image_lib->display_errors();
        }else{
            echo "Thumb creado";
        }
        $this->image_lib->clear();
    }

    // Función para descargar los archivos desde servidor
    public function descargarArchivo($id){
        $consultaArchivo = $this->DashboardDB->devolverArchivo($id);
        $descarga = file_get_contents('./'.$consultaArchivo[0]->ruta);
        force_download($consultaArchivo[0]->nombre,$descarga);
    }

    // Función para eliminar archivos del servidor
    public function eliminarArchivo(){
        $ruta = $_POST['ruta'];
        unlink('./'.$ruta);
        $this->DashboardDB->eliminarArchivo($ruta);

        redirect('Dashboard');
    }

    // Función para eliminar archivos del servidor
    public function eliminarCarpeta(){
        $ruta = $_POST['ruta'];
        $this->rmDir_rf($ruta);
        $this->DashboardDB->eliminarArchivosDeCarpeta($ruta);
        redirect('Dashboard');
    }

    public function rmDir_rf($ruta){
        foreach(glob($ruta . "/*") as $archivos_carpeta){             
            if (is_dir($archivos_carpeta)){
            $this->rmDir_rf($archivos_carpeta);
            } else {
            unlink($archivos_carpeta);
            }
        }
        rmdir($ruta);
    }

    // Función mostrarArchivos devuelve una respuesta de tipo mysqli
    public function mostrarArchivos(){
        $archivosScan = [];
        $directorio = "cargados/";
        $ficheros = scandir($directorio);
        array_shift($ficheros);
        array_shift($ficheros);
        foreach ($ficheros as $elemento) {
            if(!is_dir($directorio.$elemento)){
                array_push($archivosScan,$elemento);
            }
        }

        $archivos = [];
        $archivosPrev = $this->DashboardDB->devolverArchivos();
        while($fila = mysqli_fetch_array($archivosPrev)){
            if(in_array($fila['id_categoria'],$this->session->userdata('permisos'))){
                if(in_array($fila['nombre'],$archivosScan)){
                    array_push($archivos, $fila);
                }
            }
        }
        return $archivos;
    }

    public function escanearFolders($ruta){
        $carpetas = [];
        $directorio = $ruta;
        $ficheros = scandir($directorio);
        array_shift($ficheros);
        array_shift($ficheros);
        foreach ($ficheros as $elemento) {
            if(is_dir($directorio.$elemento)){
                $info = $this->DashboardDB->infoFoldersRuta($directorio.$elemento);
                while($fila = mysqli_fetch_array($info)){
                    array_push($carpetas, $fila);
                }
            }
        }
        return $carpetas;
    }

    public function crearNavegador($path){
        $carpetasNavegador = [];
        while($path != 'cargados'){
            $info = $this->DashboardDB->infoFoldersRuta($path);
            while($fila = mysqli_fetch_array($info)){
                array_unshift($carpetasNavegador, $fila);
            }
            $path = substr($path,0,strrpos($path,'/'));
        }
        return $carpetasNavegador;
    }

    public function folder($id){
        $this->validarTmpUsrs();

        if($this->session->userdata('correo')){
            $info = $this->DashboardDB->infoFoldersId($id);
            $nombreLimpio = $info[0]->nombre;
            $directorio = $info[0]->ruta;
            $this->session->set_userdata('dirActual',$directorio.'/');
            
            $archivos = [];
            $archivosScan = [];
            $carpetas = $this->escanearFolders($directorio.'/');
            
            $navegador = $this->crearNavegador($directorio);

            $ficheros = scandir($directorio);
            array_shift($ficheros);
            array_shift($ficheros);
            foreach ($ficheros as $elemento) {
                if(!is_dir($directorio.$elemento)){
                    array_push($archivosScan,$elemento);
                }
            }
            
            $archivosPrev = $this->DashboardDB->devolverArchivos();
            while($fila = mysqli_fetch_array($archivosPrev)){
                if(in_array($fila['id_categoria'],$this->session->userdata('permisos'))){
                    if(in_array($fila['nombre'],$archivosScan)){
                        array_push($archivos, $fila);
                    }
                }
            }
            
            if($this->session->userdata('rol') === '1'){
                $cont['roles'] = $this->consultaRoles();
            }
            $cont['categorias'] = $this->mostrarCategorias();
            $cont['usuarios'] = $this->mostrarUsuarios();
            $cont['archivos'] = $archivos;
            $cont['folders'] = $carpetas;
            $cont['navegador'] = $navegador;
            $cont['tituloPagina'] = $nombreLimpio;
            $this->load->view('comun/head',$cont);
            $this->load->view('Dashboard',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    // Función mostrarArchivos utilizada por JS para cargar el slider
    public function mostrarArchivosAs(){
        $archivos = $this->DashboardDB->devolverArchivos();
        $datosArchivos = [];
        while($fila = mysqli_fetch_array($archivos)){
            if($fila['tipo'] == '1' || $fila['tipo'] == '11'){
                array_push($datosArchivos,$fila);
            }
        }
        echo json_encode($datosArchivos);
    }

    // Función categoria que despliega en el panel una categoria en especifico, recibe un id de categoría y su descripción
    public function categoria($id_cat,$desripcion){
        $this->validarTmpUsrs();

        if($this->session->userdata('correo')){
            $this->session->set_userdata('dirActual','cargados/');
            if($this->session->userdata('rol') === '1'){
                $cont['roles'] = $this->consultaRoles();
            }

            $archivos = [];
            $archovosTipo = $this->DashboardDB->devolverArchivosTipo($id_cat);
            while($fila = mysqli_fetch_array($archovosTipo)){
                array_push($archivos,$fila);
            }
            
            $cont['categorias'] = $this->mostrarCategorias();
            $cont['usuarios'] = $this->mostrarUsuarios();
            $cont['archivos'] = $archivos;
            $cont['tituloPagina'] = $desripcion;
            $this->load->view('comun/head',$cont);
            $this->load->view('Dashboard',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    // Funcion devolverRuta es llamada por JS de forma asincrona para mostrar las previsualizaciones
    public function devolverRuta($id){
        $fila = $this->DashboardDB->devolverArchivo($id);
        echo json_encode($fila[0]);
    }

    // Función confirmarEliminar despliega la vista para que el usuario confirme para eliminar un archivo
    public function confirmarEliminar($id){
        $this->validarTmpUsrs();
        
        if($this->session->userdata('correo')){
            $consultaArchivo = $this->DashboardDB->devolverArchivo($id);
            $cont['nombreArchivo'] = $consultaArchivo[0]->nombre;
            $cont['rutaArchivo'] = $consultaArchivo[0]->ruta;
            $cont['idArchivo'] = $id;
            $cont['tituloPagina'] = 'Confirmar para eliminar';
            $this->load->view('comun/head',$cont);
            $this->load->view('comun/header');
            $this->load->view('accion/confirmarEliminar',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    // Función confirmarEliminarCarpeta despliega la vista para que el usuario confirme para eliminar una carpeta
    public function confirmarEliminarCarpeta($id){
        $this->validarTmpUsrs();
        
        if($this->session->userdata('correo')){
            $consultaCarpeta = $this->DashboardDB->devolverCarpeta($id);
            $cont['nombreArchivo'] = $consultaCarpeta[0]->nombre;
            $cont['rutaArchivo'] = $consultaCarpeta[0]->ruta;
            $cont['idArchivo'] = $id;
            $cont['tituloPagina'] = 'Confirmar para eliminar la carpeta';
            $this->load->view('comun/head',$cont);
            $this->load->view('comun/header');
            $this->load->view('accion/confirmarEliminarCarpeta',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    // Función consultaRoles devuelve una respuesta de tipo MySQL
    public function consultaRoles(){
        return $this->DatosUsuario->consultaRoles();
    }

/*
 ---------------------------------------------- Funciones para Roles
*/

    // Función Roles despliega los roles existentes para su administración
    public function roles(){
        $this->validarTmpUsrs();

        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $permisosPrev = $this->DashboardDB->consultaPermisos();
            $permisosRoles = [];
            
            while($filaPerm = mysqli_fetch_array($permisosPrev)){
                array_push($permisosRoles, $filaPerm);
            }
            
            $cont['roles'] = $this->consultaRoles();
            $cont['categorias'] = $this->mostrarCategorias();
            $cont['permisosRoles'] = $permisosRoles;
            $conte['roles'] = $this->consultaRoles();
            $conte['categorias'] = $cont['categorias'];
            $conte['tituloPagina'] = 'Administrar Roles';
            $this->load->view('comun/head',$conte);
            $this->load->view('comun/header',$conte);
            $this->load->view('Roles',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    // Función editarRol actualiza el rol en cuestión
    public function editarRol($id){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $nombreRolNuevo = $this->input->post('nombreRolNuevo');
            $checksCat = json_decode($this->input->post('checksCat'));

            $this->DatosUsuario->actualizarRol($id,$nombreRolNuevo);
            // Antes de asignar los nuevos permisos de lectura, primero se eliminan los actuales para que no haya conflicto
            $this->DatosUsuario->eliminarPermisosRol($id);
            foreach($checksCat as $check){
                if($check != NULL){
                    $this->DatosUsuario->registroPermisos($id,$check);
                }
            }
        }else{
            redirect('Login');
        }
    }

    // Función nuevoRol crea un nuevo rol xd
    public function nuevoRol(){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $nombreRolNuevo = $this->input->post('nombreRolNuevo');
            $checksCat = json_decode($this->input->post('checksCat'));

            $respuesta = $this->DatosUsuario->registrarRol($nombreRolNuevo);
            $respuesta = $respuesta->result();

            foreach($checksCat as $check){
                if($check != NULL){
                    $this->DatosUsuario->registroPermisos($respuesta[0]->maxid,$check);
                }
            }
        }else{
            redirect('Login');
        }
    }

    // Función eliminarRol elimina un rol xd
    public function eliminarRol($idRol){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            if($idRol !== '1'){ // No se puede eliminar al usuario Admin
                $usrsPorRol = $this->DatosUsuario->usrsPorRol($idRol);
                if($usrsPorRol->num_rows()>0){
                    echo 'false';
                }else{
                    $this->DatosUsuario->eliminarPermisosRol($idRol);
                    $this->DatosUsuario->eliminarRol($idRol);
                    echo 'true';
                }
            }else{
                echo "Violacion de protocolo, su dirección IP será proporcionada a las autoridades correspondientes en su País";
            }
        }else{
            redirect('Login');
        }
    }

/*
 ---------------------------------------------- Funciones para Usuarios
*/

    // Función usuarios despliega los usuarios existentes para su administración
    public function usuarios(){
        $this->validarTmpUsrs();

        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $roles = [];
            $cont['roles'] = $this->consultaRoles();
            $conte['roles'] = $this->consultaRoles();
            while($filaRol = mysqli_fetch_array($cont['roles'])){
                array_push($roles, $filaRol);
            }
            $cont['roles'] = $roles;
            $cont['usuarios'] = $this->DatosUsuario->consultaUsuarios();
            $conte['categorias'] = $this->mostrarCategorias();
            $conte['tituloPagina'] = 'Administrar Usuarios';
            $this->load->view('comun/head',$conte);
            $this->load->view('comun/header');
            $this->load->view('Usuarios',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }
    
    // Función editarUsuario actualiza el usuario en cuestión
    public function editarUsuario(){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $id = $this->input->post('usrEditId');
            if($id !== '1'){
                $nombreNuevo = $this->input->post('nombreNuevo'.$id);
                $usrRolNuevo = $this->input->post('usrRolNuevo'.$id);

                $this->DatosUsuario->actualizarUsuario($id, $nombreNuevo, $usrRolNuevo);
                redirect('Dashboard/usuarios');
            }else{
                echo "Violacion de protocolo, su dirección IP será proporcionada a las autoridades correspondientes en su País";
            }
        }else{
            redirect('Login');
        }
    }

    // Función eliminarUsuario elimina el usuario. No se puede eliminar al usuario SuperAdministrador
    public function eliminarUsuario($idusuario){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            if($idusuario !== '1'){
                $this->DatosUsuario->eliminarUsuario($idusuario);
                redirect('Dashboard/usuarios');
            }else{
                echo "Violacion de protocolo, su dirección IP será proporcionada a las autoridades correspondientes en su País";
            }
        }else{
            redirect('Login');
        }
    }
    // Función nuevoUsuario, registra al nuevo usuario en la BD y envia el correo de notificación al usuario
    public function nuevoUsuario(){
        if($this->session->userdata('rol') === '1' && $this->session->userdata('correo')){
            $nombreNuevo = $this->input->post('nombreNuevo');
            $correoNuevo = $this->input->post('correoNuevo');
            $pwdNuevo = md5($this->input->post('pwdNuevo'));
            $rolNuevo = $this->input->post('rolNuevo');
            $checkTmpUsr = $this->input->post('tmpUsr');
            $checkSubir = $this->input->post('checkSubir');
            $checkEliminar = $this->input->post('checkEliminar');
            $selectTiempo = $this->input->post('selectTiempo');

            $resultado = $this->DatosUsuario->consultaUsuario($correoNuevo);
            if($resultado->num_rows()>0){
                echo 'false';
            }else{
                $cad = NULL;
                if($checkTmpUsr === 'true'){
                    $fecha = date('Y-m-d');
                    if($selectTiempo == '72'){
                        $cad = strtotime('+3 day', strtotime($fecha));
                    }else if($selectTiempo == '48'){
                        $cad = strtotime('+2 day', strtotime($fecha));
                    }else{
                        $cad = strtotime('+1 day', strtotime($fecha));
                    }
                    $cad = date('Y-m-d', $cad);
                    $cad .= date(" H:i:s");
                }
                $subir = '0';
                $eliminar = '0';
                if($checkSubir === 'true'){
                    $subir = '1';
                }
                if($checkEliminar === 'true'){
                    $eliminar = '1';
                }
                $this->DatosUsuario->registro($nombreNuevo, $correoNuevo, $pwdNuevo, $rolNuevo, $cad, $subir, $eliminar);
                $resultado = $this->DatosUsuario->consultaUsuario($correoNuevo);
                $resultado = $resultado->result();
                $mensaje = '<h2>Has solicitado la creación de tu usuario para InvirtualCloud.</h2><br>';
                if($resultado[0]->id_rol == '1'){
                    $mensaje .= '<p style="font-weight: bold;">Ahora eres Administrador de InvirtualCloud!!!</p><br>';
                }else{
                    $mensaje .= '<p style="font-weight: bold;">Ahora eres un usuario de InvirtualCloud!!!</p><br>';
                }
                if($cad != NULL){
                    $mensaje .= '<p>Tu usuario es temporal y caducará en '.$selectTiempo.' Hrs</p><br>';
                }
                $mensaje .= '<p>Da click en el siguiente enlace para configurar una nueva contraseña</p><br>';
                $mensaje .= '<a href="'.base_url('index.php/Login/registrarse/').$resultado[0]->idusuario.'" ';
                $mensaje .= 'style="padding: 10px; color:white; background: #159eee; border-radius: 10px; text-decoration: none;"';
                $mensaje .= '>Terminar Registro</a>';

                $this->load->library('email');

                $this->email->set_mailtype('html');
                $this->email->from('soporte@invirtual.mx', 'Admin Invirtual');
                $this->email->to($correoNuevo, $nombreNuevo);
                $this->email->subject('Configura tu nuevo Usuario Invirtual');
                $this->email->message($mensaje);

                if($this->email->send()){
                    echo 'true';
                }else{
                    echo 'false';
                }
            }
        }else{
            $this->index();
        }

	}

/*
 ---------------------------------------------- Funciones para Categorías
*/

    // Función categorías despliega las categorías existentes para su administración
    public function categorias(){
        $this->validarTmpUsrs();

        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $categorias = [];
            $cont['categorias'] = $this->DashboardDB->consultaCategorias();
            $conte['roles'] = $this->consultaRoles();
            $conte['categorias'] = $this->mostrarCategorias();
            $conte['tituloPagina'] = 'Administrar Categorias';
            $this->load->view('comun/head',$conte);
            $this->load->view('comun/header');
            $this->load->view('Categorias',$cont);
            $this->load->view('comun/footer');
        }else{
            redirect('Login');
        }
    }

    public function editarCategoria(){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $id = $this->input->post('catEditId');
            $nombreNuevoCat = $this->input->post('nombreNuevoCat'.$id);
    
            $this->DashboardDB->actualizarCategoria($id, $nombreNuevoCat);
            redirect('Dashboard/categorias');
        }else{
            redirect('Login');
        }
    }

    // Función eliminarCategoria elimina la categoría en cuestión
    public function eliminarCategoria($idcat){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $archPorcat = $this->DashboardDB->archPorcat($idcat);
            // Condicional por si existen usuarios ligados a la categoria
            if($archPorcat->num_rows()>0){
                echo 'false';
            }else{
                $this->DashboardDB->eliminarPermisosCat($idcat);
                $this->DashboardDB->eliminarCategoria($idcat);
                echo 'true';
            }
        }else{
            redirect('Login');
        }
    }

    // Función nuevaCategoria, crea una nueva categoria
    public function nuevaCategoria(){
        if($this->session->userdata('correo') && $this->session->userdata('rol') === '1'){
            $nombreCatNueva = $this->input->post('nombreCatNueva');
            $this->DashboardDB->registrarCategoria($nombreCatNueva);
    
            // Despues de crear una nueva categoria, se actualizan los permisos en los datos de sesión del usuario, esto porque de otro modo no se puede ver
            $resultado = $this->DatosUsuario->permisos($this->session->userdata('rol'));
            $permisosQuery = $resultado->result_id;
            $permisos = [];
            while($perm = mysqli_fetch_array($permisosQuery)){
                array_push($permisos, $perm['id_cat']);
            }
            $this->session->set_userdata('permisos',$permisos);
            redirect('Dashboard/categorias');
        }else{
            redirect('Login');
        }
    }
}
