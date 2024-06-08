<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Usuario
{
	//Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
  }

	public function validar_usuario($idusuario, $user) {
    $validar_user = empty($idusuario) ? "" : "AND u.idusuario != '$idusuario'" ;
    $sql = "SELECT u.idusuario, u.login, u.password, u.estado FROM usuario AS u WHERE u.login = '$user' $validar_user;";
    $buscando =  ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; }

    if (empty($buscando['data'])) { return true; }else { return false; }
  }

	public function listarmarcados($idusuario)	{		
		$sql = "SELECT * from usuario_permiso where idusuario='$idusuario'";
		return ejecutarConsultaArray($sql); 		
	}

	public function verificar($login, $clave) {

    $sql = "SELECT u.idusuario, p.idpersona, p.nombres, p.apellidos, p.tipo_persona, p.numero_documento, p.foto_perfil
            FROM usuario AS u
            INNER JOIN persona AS p ON u.idpersona = p.idpersona
            WHERE u.login = '$login' AND u.password = '$clave'
            AND u.estado = '1' AND u.estado_delete = '1';";
    $user = ejecutarConsultaSimpleFila($sql); 
    
    if ($user['status'] == false) {  
        return $user; 
    }

    $filtro_html1 = ''; $filtro_html2 = '';

    if ($user['status']) {
      if ($user['data']['tipo_persona'] == 'Coordinador') {
        // Lógica para Coordinador
      } else if ($user['data']['tipo_persona'] == 'Docente') {
        // Lógica para Docente
      } else if ($user['data']['tipo_persona'] == 'Estudiante') {
        $sql_1 = "SELECT p.nombres, c.idcarrera AS filtro_a, c.nombre_carrera, s.idsemestre AS filtro_b, s.periodo, epi.idequipos_pi AS filtro_c, epi.nombre_equipo
                  FROM usuario AS u
                  INNER JOIN persona AS p ON u.idpersona = p.idpersona
                  INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
                  INNER JOIN semestre AS s ON e.idsemestre = s.idsemestre
                  INNER JOIN carrera as c ON s.idcarrera = c.idcarrera
                  INNER JOIN equipos_pi AS epi ON e.idequipo = epi.idequipos_pi
                  WHERE p.tipo_persona = 'Estudiante' AND u.login = '$login' AND u.password = '$clave'
                  AND u.estado = '1' AND u.estado_delete = '1'
                  ORDER BY s.periodo DESC LIMIT 1;";
        $filtros = ejecutarConsultaSimpleFila($sql_1);
      }

    } else {
        echo 'Error en la consulta: ' . $user['message'];
    }

    $data = [
        'status' => true,
        'message' => 'todo okey',
        'data' => [
            'usuario' => $user['data'],
            'filtro_user' => $filtros['data']
        ]
    ];

    return $data;
}

	public function last_sesion($idusuario)	{
		$sql = "UPDATE usuario SET last_sesion = CURRENT_TIMESTAMP WHERE  idusuario='$idusuario';";
		 ejecutarConsulta($sql);
		$sql1 = "INSERT INTO bitacora_sesion (idusuario) VALUES ('$idusuario')";
		return ejecutarConsulta($sql1); 	
	}

	public function historial_sesion($idusuario) {
   
    $sql = "SELECT DATE_FORMAT(bs.fecha_sesion, '%d/%m/%Y %h:%i %p') AS last_sesion , MONTHNAME(bs.fecha_sesion) AS nombre_mes, DAYNAME(bs.fecha_sesion) AS nombre_dia
		FROM bitacora_sesion as bs WHERE idusuario = '$idusuario' ORDER BY bs.fecha_sesion DESC;";
    return ejecutarConsultaArray($sql); 
  }
}
