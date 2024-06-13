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
    $sql = "SELECT pp.idperfil_del_pi, pp.titulo_proyecto, pp.idnum_equipo, pp.fecha_inicio, pp.fecha_cierre, pp.descripcion_proyecto, pp.link_prototipo
    FROM perfil_del_pi AS pp
    INNER JOIN equipos_pi AS epi ON pp.idnum_equipo = epi.idequipos_pi
    WHERE epi.idequipos_pi = '$idequipo' AND pp.estado = '1' AND pp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function lista_hitos($idequipo){
    $sql = "SELECT h.idhitos, pp.idperfil_del_pi, h.titulo_hito, h.descripcion, h.fecha_entrega, h.estado_activo
            FROM hitos AS h
            INNER JOIN perfil_del_pi AS pp ON pp.idperfil_del_pi = h.idperfil_pi
            INNER JOIN equipos_pi AS epi ON pp.idnum_equipo = epi.idequipos_pi
            WHERE epi.idequipos_pi = '$idequipo' AND h.estado = '1' AND h.estado_delete = '1';";
    return ejecutarConsulta($sql);
  }

  public function mostrar_hito($idhitos){
    $sql = "SELECT * FROM hitos WHERE idhitos = '$idhitos'";
    return ejecutarConsultaSimpleFila($sql);
  }
  public function editar_perfil($ideq, $titulo_p, $descripcion_p, $fecha_i, $fecha_e, $link_p){
    $sql_0 = "SELECT pp.idperfil_del_pi, epi.idequipos_pi
              FROM equipos_pi AS epi
              INNER JOIN perfil_del_pi AS pp ON epi.idequipos_pi = pp.idnum_equipo
              WHERE epi.idequipos_pi = '$ideq' AND epi.estado = '1' AND epi.estado_delete = '1';";
    $result = ejecutarConsultaSimpleFila($sql_0);
  
    $idperfil = ''; $idequipo = '';
    if ($result['status']) {

      if (!empty($result['data']['idperfil_del_pi'])) {
        $idperfil = $result['data']['idperfil_del_pi'];
      } else {
        $idperfil = null;
      }

      if (!empty($result['data']['idequipos_pi'])) {
        $idequipo = $result['data']['idequipos_pi'];
      } else {
        $idequipo = null;
      }

    } else {
      echo 'Error en la consulta: ' . $result['message'];
    }

    $sql_1 = "UPDATE perfil_del_pi SET idnum_equipo = '$idequipo', titulo_proyecto = '$titulo_p', descripcion_proyecto = '$descripcion_p', fecha_inicio = '$fecha_i', fecha_cierre = '$fecha_e', link_prototipo = '$link_p'
    WHERE idperfil_del_pi = '$idperfil';";
    $edit_perfil = ejecutarConsulta($sql_1);  if ($edit_perfil['status'] == false) {  return $edit_perfil; }

    return $edit_perfil;
  }

  public function insertar_hito($idequipo, $nombre_hito, $fecha_hito_e, $descr_hito){
    $sql_0 = "SELECT * FROM hitos  WHERE titulo_hito = '$nombre_hito';";
    $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}

    $buscar_perfil_p = "SELECT pp.idperfil_del_pi
    FROM perfil_del_pi AS pp
    INNER JOIN equipos_pi AS epi ON pp.idnum_equipo = epi.idequipos_pi
    WHERE epi.idequipos_pi = '$idequipo' AND pp.estado = '1' AND pp.estado_delete = '1' LIMIT 1";
    $perfil = ejecutarConsultaSimpleFila($buscar_perfil_p);

    $idperfil_p = $perfil['data']['idperfil_del_pi'];
      
    if ( empty($existe['data']) ) {
			$sql="INSERT INTO hitos(idperfil_pi, titulo_hito, descripcion, fecha_entrega)VALUES('$idperfil_p', '$nombre_hito', '$descr_hito', '$fecha_hito_e')";
			$insertar =  ejecutarConsulta_retornarID($sql, 'C'); if ($insertar['status'] == false) {  return $insertar; } 
			
			return $insertar;
		} else {
			$info_repetida = ''; 

			foreach ($existe['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
					<span class="font-size-15px text-danger"><b>Título: </b>'.$value['titulo_hito'].'</span><br>
					<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
					<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
					<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
		}		
  }

  public function editar_hito($idhitos, $idperfil_pi, $nombre_hito, $fecha_hito_e, $descr_hito) {
    $sql_0 = "SELECT * FROM hitos  WHERE titulo_hito = '$nombre_hito';";
    $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}

    if ( empty($existe['data']) ) {
			$sql="UPDATE hitos SET idperfil_pi='$idperfil_pi', titulo_hito='$nombre_hito', descripcion ='$descr_hito', fecha_entrega='$fecha_hito_e' WHERE idhitos='$idhitos'";
			$editar =  ejecutarConsulta($sql);	if ( $editar['status'] == false) {return $editar; } 
			
			return $editar;
		} else {
			$info_repetida = ''; 

			foreach ($existe['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
					<span class="font-size-15px text-danger"><b>Título: </b>'.$value['titulo_hito'].'</span><br>
					<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
					<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
					<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
		}	
  }

  public function desactivar_hito($idhitos) {
		$sql="UPDATE hitos SET estado='0' WHERE idhitos='$idhitos'";
		$desactivar= ejecutarConsulta($sql, 'T');
		return $desactivar;
	}

	public function activar_hito($idhitos) {
		$sql="UPDATE hitos SET estado='1' WHERE idhitos='$idhitos'";
		return ejecutarConsulta($sql);
	}

	public function eliminar_hito($idhitos) {
		$sql="UPDATE hitos SET estado_delete='0' WHERE idhitos='$idhitos'";
		$eliminar =  ejecutarConsulta($sql, 'D');	if ( $eliminar['status'] == false) {return $eliminar; }  
		return $eliminar;
	}


}