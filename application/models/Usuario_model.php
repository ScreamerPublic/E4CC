<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    // Constructor del modelo
    public function __construct() {
        parent::__construct();
        // Cargar la base de datos
        $this->load->database();
    }

    function obtenerUsuario( $id ){
        $this->db->where('id_usuario',$id);
        return $this->db->get('usuarios')->row_array();
    }
    
    
    function obtenerHistorialPagos($post){
        $this->db->where('idusuario',$post['id']);
        $pagos = $this->db->get('registros_pagos')->result();

        return json_encode($pagos);
    }
    
    function obtenerHistorialUsuarios(){
        $pagos = $this->db->get('usuarios')->result();

        return json_encode($pagos);
    }

    // Método para autenticar usuarios
    public function autenticar($nombreUsuario, $contrasena) {
        $this->db->select('id_usuario, nombre_usuario, contrasena, estado, rol,nombres,apellidos,correo_electronico');
        $this->db->where('nombre_usuario', $nombreUsuario);
        $this->db->where('estado', 'activo');
    
        $query = $this->db->get('usuarios');
    
        if ($query->num_rows() > 0) {
            $usuario = $query->row_array();
    
            // Verify password using bcrypt
            if (password_verify($contrasena, $usuario['contrasena'])) {
                if ($usuario['estado'] == 'activo') {
                    return $usuario; // Devuelve los datos del usuario
                } else {
                    return 'Usuario inactivo';
                }
            } else {
                return 'Contraseña inválida';
            }
        } else {
            return 'Usuario no encontrado';
        }
    }
     
    function guardarPagos( $post ){
        try{

            $datos['tipo_pago'] = $post['tipoPago'];
            $datos['cantidad'] = $post['cantidad'];
            $datos['monto_pagar'] = $post['montoPagar'];
            $datos['fecha_pagar'] = date('Y-m-d',strtotime($post['fechaPagar']));
            $datos['comentarios'] = $post['comentarios'];
            $datos['idusuario'] = $post['id_usuario'];

            $this->db->insert('registros_pagos',$datos);
            if( $this->db->affected_rows() > 0 ){
                return 200;
            }
            return 400;
        } catch ( Exception $e ){
            return 500;
        }
        return 500;
    }
    
    function guardarUsuario( $post ){
        try{

            $post['contrasena'] = password_hash($post['contrasena'], PASSWORD_BCRYPT);

            $this->db->insert('usuarios',$post);
            if( $this->db->affected_rows() > 0 ){
                return 200;
            }
            return 400;
        } catch ( Exception $e ){
            return 500;
        }
        return 500;
    }
    
    function desactivarUsuario( $post ){
        try{
            $this->db->where('id_usuario',$post['id']);
            $this->db->set('estado','inactivo');
            $this->db->update('usuarios');
            if( $this->db->affected_rows() > 0 ){
                return 200;
            }
            return 400;
        } catch ( Exception $e ){
            return 500;
        }
        return 500;
    }
}
