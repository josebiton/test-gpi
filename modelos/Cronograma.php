<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Cronograma{
  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }


  public function tabla_crono($idequipo){
    $sql = "SELECT cg.idcromograma_tareas, cg.nombre_actividad, cg.descripcion_actividad, cg.dia_duracion, DATE_FORMAT(cg.fecha_inicio, '%d/%m/%Y') AS fecha_inicio, DATE_FORMAT(cg.fecha_cierre, '%d/%m/%Y') AS fecha_cierre, cg.es_entregable
            FROM cromograma_actividad AS cg
            INNER JOIN perfil_del_pi AS pp ON cg.idperfil_pi = pp.idperfil_del_pi
            INNER JOIN equipos_pi AS epi ON pp.idnum_equipo = epi.idequipos_pi
            WHERE epi.idequipos_pi = '$idequipo' AND cg.estado = '1' AND cg.estado_delete = '1'";
    return ejecutarConsulta($sql);
  }

  public function insertar_crono($idequipo, $nombre_a, $duracion_a, $descr_a, $fecha_i_a, $fecha_e_a, $es_entreg_a){

    $sql_0 = "SELECT pp.idperfil_del_pi, pp.titulo_proyecto, pp.fecha_inicio as fecha1
              FROM equipos_pi AS epi
              INNER JOIN perfil_del_pi AS pp ON epi.idequipos_pi = pp.idnum_equipo
              WHERE epi.idequipos_pi = '$idequipo' AND epi.estado = '1' AND epi.estado_delete = '1'";
    $perfil = ejecutarConsultaSimpleFila($sql_0);

    $idperfil = '';
    if ($perfil['status']) {

      if (!empty($perfil['data']['idperfil_del_pi'])) {
        $idperfil = $perfil['data']['idperfil_del_pi'];
      } else {
        $idperfil = null;
      }

    } else {
      echo 'Error en la consulta: ' . $perfil['message'];
    }

    $sql ="INSERT INTO cromograma_actividad(idperfil_pi, nombre_actividad, dia_duracion, descripcion_actividad, fecha_inicio, fecha_cierre, es_entregable)
    VALUES('$idperfil','$nombre_a','$duracion_a', '$descr_a','$fecha_i_a','$fecha_e_a','$es_entreg_a')";
    $insertar =  ejecutarConsulta_retornarID($sql, 'C'); if ($insertar['status'] == false) {  return $insertar; } 
    
    return $insertar;

  }

  public function editar_crono($idcromograma_tareas, $idperfil, $nombre_a, $duracion_a, $descr_a, $fecha_i_a, $fecha_e_a, $es_entreg_a){
    $sql="UPDATE cromograma_actividad SET idperfil_pi='$idperfil', nombre_actividad='$nombre_a', descripcion_actividad='$descr_a',
    dia_duracion='$duracion_a', fecha_inicio='$fecha_i_a', fecha_cierre='$fecha_e_a', es_entregable='$es_entreg_a' 
    WHERE idcromograma_tareas='$idcromograma_tareas';";
    return ejecutarConsulta($sql);
  }

  public function mostrar_crono($idcromograma_tareas){
    $sql = "SELECT cr.idcromograma_tareas, cr.idperfil_pi, cr.nombre_actividad, cr.descripcion_actividad, cr.dia_duracion, 
              cr.es_entregable, cr.fecha_inicio, cr.fecha_cierre
            FROM cromograma_actividad AS cr
            WHERE cr.idcromograma_tareas = '$idcromograma_tareas' AND cr.estado = '1' AND cr.estado_delete = '1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function guardar_doc($idcrono, $nom_entrg){
    $sql = "INSERT INTO entregables(idcronograma_actividad, nombre_archivo)VALUES('$idcrono', '$nom_entrg')";
    $insertar =  ejecutarConsulta_retornarID($sql); if ($insertar['status'] == false) {  return $insertar; } 
    return $insertar;
  }

  public function mostrar_archivos($idcrono){
    $sql = "SELECT * FROM entregables WHERE idcronograma_actividad = '$idcrono';";
    return ejecutarConsultaArray($sql);
  }

  public function mostrar_archivo($identregables){
    $sql = "SELECT * FROM entregables WHERE identregables = '$identregables';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function desactivar_crono($idcromograma_tareas) {
		$sql="UPDATE cromograma_actividad SET estado='0' WHERE idcromograma_tareas='$idcromograma_tareas'";
		$desactivar= ejecutarConsulta($sql, 'T');
		return $desactivar;
	}

	public function activar_crono($idcromograma_tareas) {
		$sql="UPDATE cromograma_actividad SET estado='1' WHERE idcromograma_tareas='$idcromograma_tareas'";
		return ejecutarConsulta($sql);
	}

	public function eliminar_crono($idcromograma_tareas) {
		$sql="UPDATE cromograma_actividad SET estado_delete='0' WHERE idcromograma_tareas='$idcromograma_tareas'";
		$eliminar =  ejecutarConsulta($sql, 'D');	if ( $eliminar['status'] == false) {return $eliminar; }  
		return $eliminar;
	}

  public function traer_fecha_crono($idequipo){
    $sql_0 = "SELECT pp.idperfil_del_pi, pp.titulo_proyecto, pp.fecha_inicio as fecha1
              FROM equipos_pi AS epi
              INNER JOIN perfil_del_pi AS pp ON epi.idequipos_pi = pp.idnum_equipo
              WHERE epi.idequipos_pi = '$idequipo' AND epi.estado = '1' AND epi.estado_delete = '1'";
    $perfil = ejecutarConsultaSimpleFila($sql_0);

    $idperfil = '';
    if ($perfil['status']) {

      if (!empty($perfil['data']['idperfil_del_pi'])) {
        $idperfil = $perfil['data']['idperfil_del_pi'];
      } else {
        $idperfil = null;
      }

    } else {
      echo 'Error en la consulta: ' . $perfil['message'];
    }

    $sql_1 = "SELECT cr.idcromograma_tareas, cr.fecha_inicio AS fechaa, cr.fecha_cierre AS fecha1
              FROM cromograma_actividad AS cr
              INNER JOIN perfil_del_pi AS pp ON cr.idperfil_pi = pp.idperfil_del_pi
              WHERE pp.idperfil_del_pi = '$idperfil' AND cr.estado = '1' AND cr.estado_delete = '1' 
              ORDER BY cr.fecha_cierre DESC LIMIT 1";
    $fecha_crono = ejecutarConsultaSimpleFila($sql_1);

    if(empty($fecha_crono['data'])){
      return $perfil;
    } else {
      return $fecha_crono;
    }
  }


}