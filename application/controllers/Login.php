<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct(){
        parent :: __construct();
        $this->load->model('Usuario_model');
        $this->load->helper('registro');
    }

    public function index() {
        // Verificar si existe una sesión de usuario
        if ($this->session->has_userdata('usuario')) {
            // La sesión existe, redirigir al usuario a la vista bienvenido
            $datos['usuario'] = $this->session->userdata('usuario');
            if( $datos['usuario']['rol'] == 'usuario' ){
                redirect('Login/miHistorial');
            }else
            if( $datos['usuario']['rol'] == 'administrador' ){
                $this->load->view('bienvenido_administrador',$datos);
            }
            else{
                $this->cerrar_sesion();
            }
        } else {
            // La sesión no existe, redirigir al usuario a la página de login
            $this->load->view('login');
        }
    }

    function miHistorial(){
        // Verificar si existe una sesión de usuario
        if ($this->session->has_userdata('usuario') ) {
            // La sesión existe, redirigir al usuario a la vista bienvenido
            $datos['usuario'] = $this->session->userdata('usuario');
            if( $datos['usuario']['rol'] == 'usuario' || isset($_GET['id'])){
                if( isset($_GET['id']) && $_GET['id'] > 0){
                    $datos['usuario'] = $this->Usuario_model->obtenerUsuario( $_GET['id'] );
                }
                $this->load->view('bienvenido',$datos);
            }else
            if( $datos['usuario']['rol'] == 'administrador' && !isset($_GET['id']) ){
                redirect('/');
            }
            else{
                $this->cerrar_sesion();
            }
        } else {
            // La sesión no existe, redirigir al usuario a la página de login
            $this->load->view('login');
        }
    }

    public function autenticar() {
        // Obtener los datos enviados por la vista
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Acceder a los datos
        $nombreUsuario = $data['username'];
        $contrasena = $data['password'];
    
        // Autenticar al usuario
        $usuario = $this->Usuario_model->autenticar($nombreUsuario, $contrasena);
    
        if (is_array($usuario)) {
            // Iniciar sesión
            $this->session->set_userdata('usuario', $usuario);
            $response = [
                'success' => true,
                'message' => 'Autenticación exitosa'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => $usuario
            ];
        }
    
        // Devolver la respuesta en formato JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function cerrar_sesion() {
        $this->session->unset_userdata('usuario');
        redirect('login');
    }
    
    function obtenerFormulario(){
        return obtenerFormulario();
    }

    function guardarPagos(){
        $estado = $this->Usuario_model->guardarPagos($this->input->post());

        if( $estado == 200 ){
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Datos almacenados';
            $respuesta['tipo'] = 'alert-success';
        }else{
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Algo salio mal intentelo mas tarde';
            $respuesta['tipo'] = 'alert-warning';
        }
        echo json_encode( $respuesta );
    }
    
    function guardarUsuario(){
        $estado = $this->Usuario_model->guardarUsuario($this->input->post());

        if( $estado == 200 ){
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Datos almacenados';
            $respuesta['tipo'] = 'alert-success';
        }else{
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Algo salio mal intentelo mas tarde';
            $respuesta['tipo'] = 'alert-warning';
        }
        echo json_encode( $respuesta );
    }
    
    function desactivarUsuario(){
        $estado = $this->Usuario_model->desactivarUsuario($this->input->post());

        if( $estado == 200 ){
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Datos almacenados';
            $respuesta['tipo'] = 'alert-success';
        }else{
            $respuesta['estado'] = $estado;
            $respuesta['mensaje'] = 'Algo salio mal intentelo mas tarde';
            $respuesta['tipo'] = 'alert-warning';
        }
        echo json_encode( $respuesta );
    }

    function obtenerHistorialPagos(){
        echo $this->Usuario_model->obtenerHistorialPagos( $this->input->post() );
    }
    
    function obtenerHistorialUsuarios(){
        echo $this->Usuario_model->obtenerHistorialUsuarios();
    }
}
