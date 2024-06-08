<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Perfil_proyecto{
  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }


  public function mostrar_perfil_p($idequipo){
    $sql = "SELECT pp.idperfil_del_pi, pp.titulo_proyecto, pp.idnum_equipo
    FROM perfil_del_pi AS pp
    INNER JOIN equipos_pi AS epi ON pp.idnum_equipo = epi.idequipos_pi
    WHERE epi.idequipos_pi = '$idequipo' AND pp.estado = '1' AND pp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar_tabla_hitos($id){
    $sql = "SELECT h.idhitos, h.idperfil_pi, h.titulo_hito, h.descripcion, h.fecha_entrega, h.estado_activo
    FROM usuario AS u
    INNER JOIN persona AS p ON u.idpersona = p.idpersona
    INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
    INNER JOIN equipos_pi AS epi ON e.idequipo = epi.idequipos_pi
    INNER JOIN perfil_del_pi AS pp ON epi.idequipos_pi = pp.idnum_equipo
    INNER JOIN hitos AS h ON pp.idperfil_del_pi = h.idperfil_pi
    WHERE u.idusuario = '$id' AND h.estado = '1' AND h.estado_delete = '1';";
    return ejecutarConsulta($sql);
  }

  public function editar_perfil($id, $titulo_p, $descripcion_p, $fecha_i, $fecha_e, $link_p){
    $sql_0 = "SELECT pp.idperfil_del_pi, epi.idequipos_pi
    FROM usuario AS u
    INNER JOIN persona AS p ON u.idpersona = p.idpersona
    INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
    INNER JOIN equipos_pi AS epi ON e.idequipo = epi.idequipos_pi
    INNER JOIN perfil_del_pi AS pp ON epi.idequipos_pi = pp.idnum_equipo
    WHERE u.idusuario = '$id' AND pp.estado = '1' AND pp.estado_delete = '1';";
    $result = ejecutarConsultaSimpleFila($sql_0);
  
    $idperfil = ''; $idequipo = '';
    if ($result['status']) {
      if (isset($result['data']['idperfil_del_pi'])) {
        $idperfil = $result['data']['idperfil_del_pi'];
      }
      if (isset($result['data']['idequipos_pi'])) {
        $idequipo = $result['data']['idequipos_pi'];
      }
    } else {
      echo 'Error en la consulta: ' . $result['message'];
    }

    $sql_1 = "UPDATE perfil_del_pi SET idnum_equipo = '$idequipo', titulo_proyecto = '$titulo_p', descripcion_proyecto = '$descripcion_p', fecha_inicio = '$fecha_i', fecha_cierre = '$fecha_e', link_prototipo = '$link_p'
    WHERE idperfil_del_pi = '$idperfil';";
    return ejecutarConsulta($sql_1);
  }


}