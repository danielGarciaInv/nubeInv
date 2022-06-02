<?php
class DashboardDB extends CI_model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function registroArchivoPre($tamano, $fecha, $tipo){
        return $this->db->insert('archivo_cargado',['tamano'=>$tamano, 'fecha'=>$fecha, 'tipo'=>$tipo]);
    }
    
    public function registroArchivo($nombre, $ruta, $tamano, $fecha, $tipo, $cat, $categoria){
        return $this->db->insert('archivo_cargado',['nombre'=>$nombre, 'ruta'=>$ruta, 'tamano'=>$tamano, 'fecha'=>$fecha, 'tipo'=>$tipo, 'fileType'=>$cat, 'id_categoria'=>$categoria]);
    }

    public function registrarCarpeta($nombre, $path){
        return $this->db->query("INSERT INTO carpeta VALUES (NULL,'$nombre','$path');");
    }

    public function eliminarArchivo($ruta){
        return $this->db->query("DELETE FROM archivo_cargado WHERE ruta = '$ruta';");
    }

    public function modificarArchivo($id,$nombreEditado){
        return $this->db->query("UPDATE archivo_cargado SET nombre = '$nombreEditado' WHERE (id = '$id');");
    }

    public function devolverArchivos(){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado ORDER BY id DESC;");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivosLimit($inicio, $limite){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE ruta NOT LIKE 'cargados/%/%' ORDER BY id DESC LIMIT $inicio,$limite");
        return $consultaArchivos->result_id;
    }

    public function ultimoIdArchivos(){
        $consultaArchivos = $this->db->query("SELECT MAX(id) indice FROM archivo_cargado;");
        return $consultaArchivos->result();
    }

    public function devolverArchivosPre(){
        $consultaArchivos = $this->db->get('archivo_cargado');
        return $consultaArchivos;
    }

    public function devolverArchivosTipo($id_categoria){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE id_categoria = '$id_categoria' ORDER BY id DESC;");
        return $consultaArchivos->result_id;
    }

    public function devolverArchivo($id){
        $consultaArchivos = $this->db->query("SELECT * FROM archivo_cargado WHERE id = '$id';");
        return $consultaArchivos->result();
    }

    public function devolverCarpeta($id){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE id = '$id';");
        return $consultaArchivos->result();
    }

    public function eliminarArchivosDeCarpeta($ruta){
        $this->db->query("DELETE FROM archivo_cargado WHERE ruta LIKE '$ruta%';");
        $this->db->query("DELETE FROM carpeta WHERE ruta LIKE '$ruta%';");
    }

    public function infoFoldersRuta($ruta){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE ruta = '$ruta';");
        return $consultaArchivos->result_id;
    }

    public function infoFoldersId($id){
        $consultaArchivos = $this->db->query("SELECT * FROM carpeta WHERE id = '$id';");
        return $consultaArchivos->result();
    }
    
    public function ultimaCategoria(){
        $consultaArchivos = $this->db->query("SELECT c.descripcion descripcion, c.id id_cat FROM archivo_cargado a INNER JOIN categoria c ON a.id_categoria = c.id WHERE a.id = (SELECT MAX(id) FROM archivo_cargado);");
        $consultaArchivos = $consultaArchivos->result();
        return $consultaArchivos;
    }
    public function consultaCategorias(){
        $resultado = $this->db->query("SELECT * FROM categoria;");
        return $resultado->result_id;
    }
    public function eliminarPermisosCat($id_cat){
        return $this->db->query("DELETE FROM rol_ve_categoria WHERE id_cat = '$id_cat'");
    }
    public function consultaPermisos(){
        $resultado = $this->db->query("SELECT * FROM rol_ve_categoria;");
        return $resultado->result_id;
    }
    public function actualizarCategoria($id, $descripcion){
        return $this->db->query("UPDATE categoria SET descripcion = '$descripcion' WHERE id = '$id';");
    }
    public function registrarCategoria($descripcion){
        $this->db->query("INSERT INTO categoria VALUES(NULL,'$descripcion');");
        $this->db->query("INSERT INTO rol_ve_categoria VALUES(1,(SELECT MAX(id) FROM categoria));");
    }
    public function archPorcat($idcat){
        $resultado = $this->db->query("SELECT * FROM categoria INNER JOIN archivo_cargado ON categoria.id = archivo_cargado.id_categoria WHERE categoria.id = '$idcat';");
        return $resultado;
    }
    public function eliminarCategoria($id){
        return $this->db->query("DELETE FROM categoria WHERE id = '$id'");
    }
    public function usuariosCorreo($id_cat){
        $correos = $this->db->query("SELECT u.correo FROM usuario u INNER JOIN rol r ON u.id_rol = r.id INNER JOIN rol_ve_categoria rvc ON r.id = rvc.id_rol WHERE rvc.id_cat = '$id_cat'");
        return $correos->result();
    }
}
?>