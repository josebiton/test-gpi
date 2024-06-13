<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Equipo{
  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }

  public function datos_equipo($idequipo){
    $sql = "SELECT idequipos_pi, nombre_equipo, titulo_equipo FROM equipos_pi WHERE idequipos_pi = '$idequipo'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar_equipo($idequipo){
    $sql = "SELECT es.idestudiante, epi.idequipos_pi, p.nombres, p.apellidos, p.numero_documento, es.rol_proyecto
            FROM estudiante AS es
            INNER JOIN equipos_pi AS epi ON es.idequipo = epi.idequipos_pi
            INNER JOIN persona AS p ON es.idpersona = p.idpersona
            where epi.idequipos_pi = '$idequipo' AND epi.estado = '1' AND epi.estado_delete = '1';";
    return ejecutarConsulta($sql);
  }

  public function mostrar_rol_estudiante($idestudiante, $idequipo){
    $sql = "SELECT es.idestudiante, p.nombres, p.apellidos, es.rol_proyecto
            FROM estudiante AS es
            INNER JOIN persona AS p ON es.idpersona = p.idpersona
            INNER JOIN equipos_pi AS epi ON es.idequipo = epi.idequipos_pi
            WHERE es.idestudiante = '$idestudiante' AND epi.idequipos_pi = '$idequipo' 
            AND epi.estado = '1' AND epi.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function editar_rol($idestudiante, $rol_e){
    $sql = "UPDATE estudiante SET rol_proyecto = '$rol_e' WHERE idestudiante = '$idestudiante'";
    return ejecutarConsulta($sql);
  }

  public function editar_titulo($idequipo, $titulo_p){
    $sql = "UPDATE equipos_pi SET titulo_equipo = '$titulo_p' WHERE idequipos_pi = '$idequipo'";
    return ejecutarConsulta($sql);
  }


}