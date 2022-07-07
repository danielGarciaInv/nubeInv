<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Controlador Api encargado de responder las peticiones que se hagan externamente a la nube.
*/
class Api extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('DashboardDB');
        $this->load->model('DatosUsuario');
    }

	public function index(){
		//$this->validarTmpUsrs();
        if(!$this->input->is_ajax_request()){
			show_404();
			return;
		}
        
	}

    public function subirimgv1(){
        header('Access-Control-Allow-Origin: https://centralinvirzo.xyz');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-xsrf-token');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Credentials: true');

        if(file_get_contents('php://input') != ''){
            
            $directorio = 'cargados/CRM_Images';
            if(!file_exists($directorio)){
                mkdir($directorio, 0775, true);
                // -------------------------------------------------------- Funcion para registrar carpeta en BD
                $this->DashboardDB->registrarCarpeta('CRM_Images', $directorio);
            }
            $directorio .= '/';
            $datos = json_decode(file_get_contents('php://input'));
            $file_b64 = $datos->evidence;

            if($this->validarImg($file_b64)){
                $file_b64 = str_replace('data:image/jpeg;base64,','',$file_b64);
                $file_b64 = str_replace('data:image/png;base64,','',$file_b64);
                $bin = base64_decode($file_b64);

                $indice = $this->DashboardDB->ultimoIdArchivos();
                $indice = $indice[0]->indice;
                $indice++;
                $nombre = 'img_';
                $nombre .= $indice . '.webp';
        
                $ruta = $directorio . $nombre;
                $fecha = date("d/m/Y");
                $tipo = 1;
                $cat = 'img';
                $categoria = '1';
    
                $size = (int) (strlen(rtrim($bin, '=')) * 6 / 8);
                $tamano = $size / 8;
                
                $binImg = imagecreatefromstring($bin);
                imagewebp($binImg,$ruta,90);
    
                $tamanoKb = $tamano * 0.001;
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
                $this->DashboardDB->registroArchivo($nombre,$ruta,$tamano,$fecha,$tipo,$cat,$categoria);
        
                $info = ['rutaAbsoluta' => base_url($ruta), 'nombre' => $nombre];
            }else{
                $info = ['error' => '¡El archivo seleccionado no es una imagen!'];
            }
            echo json_encode($info);
        }
    }

    public function subirpdfv1(){
        header('Access-Control-Allow-Origin: https://centralinvirzo.xyz');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-xsrf-token');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Credentials: true');
        
        if(file_get_contents('php://input') != ''){

            $directorio = 'cargados/CRM_Files';
            if(!file_exists($directorio)){
                mkdir($directorio, 0775, true);
                // -------------------------------------------------------- Funcion para registrar carpeta en BD
                $this->DashboardDB->registrarCarpeta('CRM_Files', $directorio);
            }
            $directorio .= '/';
            $datos = json_decode(file_get_contents('php://input'));    
            $file_b64 = $datos->pdf;

            if($this->validarPdf($file_b64)){
                $file_b64 = str_replace('data:application/pdf;base64,','',$file_b64);
                $bin = base64_decode($file_b64);
        
                $indice = $this->DashboardDB->ultimoIdArchivos();
                $indice = $indice[0]->indice;
                $indice++;
                $nombre = 'pdf_';
                $nombre .= $indice . '.pdf';

                $ruta = $directorio . $nombre;
                $fecha = date("d/m/Y");
                $tipo = 41;
                $cat = 'appl';
                $categoria = '3';
                
                $tamano = file_put_contents($ruta, $bin);
                $tamanoKb = $tamano * 0.001;
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
                $this->DashboardDB->registroArchivo($nombre,$ruta,$tamano,$fecha,$tipo,$cat,$categoria);
        
                $info = ['rutaAbsoluta' => base_url($ruta), 'nombre' => $nombre];
            }else{
                $info = ['error' => '¡El archivo seleccionado no es un PDF!'];
            }
            echo json_encode($info);
        }
    }

    function validarImg($value) {
        $explode = explode(',', $value);
        $allow = ['png', 'jpg', 'jpeg', 'webp'];
        $format = str_replace(
            [
                'data:image/',
                ';',
                'base64',
            ],
            [
                '', '', '',
            ],
            $explode[0]
        );

        // check file format
        if (!in_array($format, $allow)) {
            return false;
        }

        // check base64 format
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
            return false;
        }

        return true;
    }

    function validarPdf($value) {
        $explode = explode(',', $value);
        $allow = ['pdf'];
        $format = str_replace(
            [
                'data:application/',
                ';',
                'base64',
            ],
            [
                '', '', '',
            ],
            $explode[0]
        );

        // check file format
        if (!in_array($format, $allow)) {
            return false;
        }

        // check base64 format
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
            return false;
        }

        return true;
    }

    public function prueba(){
        echo "Hola soy el tio Barney";
    }
}
