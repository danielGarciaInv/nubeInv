<?php
class datosUsuario extends CI_model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function validarUsr($correo, $pwd){
        $resultado = $this->db->query("SELECT * FROM usuario INNER JOIN rol ON usuario.id_rol = rol.id WHERE correo = '$correo' AND pwd = '$pwd';");
		return $resultado;
    }

    public function permisos($id_rol){
        $resultado = $this->db->query("SELECT id_cat FROM rol_ve_categoria WHERE id_rol = '$id_rol'");
		return $resultado;
    }

    public function registroPermisos($id_rol, $id_cat){
        return $this->db->insert('rol_ve_categoria',['id_rol'=>$id_rol, 'id_cat'=>$id_cat]);
    }
    
    public function eliminarPermisosRol($id_rol){
        return $this->db->query("DELETE FROM rol_ve_categoria WHERE id_rol = '$id_rol'");
    }

    public function registro($nombre, $correo, $pwd, $rol, $cad, $subir, $eliminar){
        return $this->db->insert('usuario',['nombre'=>$nombre, 'pwd'=>$pwd, 'correo'=>$correo, 'id_rol'=>$rol, 'caducidad'=>$cad, 'subir'=>$subir, 'eliminar'=>$eliminar]);
    }

    public function eliminarUsuario($idusuario){
        return $this->db->query("DELETE FROM usuario WHERE idusuario = '$idusuario'");
    }
    
    public function actualizarUsuario($idusuario, $nombre, $id_rol){
        return $this->db->query("UPDATE usuario SET nombre = '$nombre', id_rol = '$id_rol' WHERE idusuario = '$idusuario';");
    }
    
    public function consultaUsuario($correo){
        $this->db->where('correo',$correo);
        $resultado = $this->db->get('usuario');
		return $resultado;
    }
    
    public function consultaUsuarios(){
        $resultado = $consultaArchivos = $this->db->query("SELECT * FROM usuario ORDER BY idusuario;");
		return $resultado->result_id;
    }
    
    public function consultaUsuariosYRoles(){
        $resultado = $consultaArchivos = $this->db->query("SELECT * FROM usuario INNER JOIN rol ON usuario.id_rol = rol.id");
		return $resultado->result_id;
    }
    
    public function consultaUsuariosTmp(){
        $resultado = $this->db->query("SELECT * FROM usuario WHERE caducidad IS NOT NULL;");
        return $resultado->result();
    }
    
    public function consultaRoles(){
        $resultado = $this->db->query("SELECT * FROM rol ORDER BY id;");
        return $resultado->result_id;
    }
    
    public function usrsPorRol($idRol){
        $resultado = $this->db->query("SELECT * FROM rol INNER JOIN usuario ON rol.id = usuario.id_rol WHERE rol.id = '$idRol';");
        return $resultado;
    }

    public function cambiarContrasena($pwd, $id){
        $this->db->query("UPDATE usuario SET pwd = '$pwd' WHERE idusuario = '$id';");
    }
    
    public function actualizarRol($id,$descr){
        return $this->db->query("UPDATE rol SET descr = '$descr' WHERE id = '$id';");
    }
    
    public function registrarRol($descr){
        $this->db->insert('rol',['descr'=>$descr]);
        return $this->db->query("SELECT MAX(id) maxid FROM rol");
    }
    
    public function eliminarRol($id){
        return $this->db->query("DELETE FROM rol WHERE id = '$id'");
    }

    public function insertarCambioPwd($idusuario, $caducidad){
        $this->db->query("INSERT INTO recuperar_pwd VALUES (NULL,'$idusuario', '$caducidad');");
        return $this->db->query("SELECT MAX(id) maxid FROM recuperar_pwd;");
    }

    public function consultarCambioPwd($id){
        $res = $this->db->query("SELECT * FROM recuperar_pwd WHERE id = '$id';");
        return $res->result();
    }

    public function eliminarCambioPwd($id){
        $this->db->query("DELETE FROM recuperar_pwd WHERE id = '$id';");
    }
}
?>