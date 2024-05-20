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

	//Implementamos un método para insertar registros
	public function insertar($idpersona, $login, $clavehash, $permisos)	{

		if (empty($permisos)) {	return [ 'status'=>'error_usuario', 'user'=> $_SESSION['user_nombre'], 'message'=>'No se ha selecionado los permisos de <b>MÓDULOS</b>','data'=>  []  ]; 	}		
     
		$sql = "INSERT INTO usuario( idpersona, login, password) VALUES ('$idpersona','$login','$clavehash')";
		$id_new = ejecutarConsulta_retornarID($sql, 'C');	if ($id_new['status'] == false) {  return $id_new; } 		

		$id = $id_new['data'];
		$zz = 0;
		$yy = 0;

		while ($zz < count($permisos)) {
			$sql_detalle = "INSERT into usuario_permiso(idusuario, idpermiso) values ('$id', '$permisos[$zz]')";
			$usr_permiso = ejecutarConsulta($sql_detalle, 'C'); if ($usr_permiso['status'] == false) {  return $usr_permiso; } 
			$zz = $zz + 1;
		}	

    return $id_new;
	}

	//Implementamos un método para editar registros
	public function editar($idusuario, $idpersona, $login, $clavehash, $permisos) {

		if (empty($permisos)) {	return [ 'status'=>'error_usuario', 'user'=> $_SESSION['user_nombre'], 'message'=>'No se ha selecionado los permisos de <b>MÓDULOS</b>','data'=>  []  ]; 	}
		

		$sql = "UPDATE usuario SET idpersona='$idpersona', login='$login', password='$clavehash' WHERE idusuario='$idusuario'";
		$edit_user = ejecutarConsulta($sql, 'U'); if ($edit_user['status'] == false) {  return $edit_user; }

		//Eliminar todos los permisos asignados para volverlos a registrar
		$sqldel = "DELETE from usuario_permiso where 	idusuario='$idusuario'";
		$del_up = ejecutarConsulta($sqldel); if ($del_up['status'] == false) {  return $del_up; }


		$zz = 0;
		$yy = 0;

		while ($zz < count($permisos)) {
			$sql_detalle = "INSERT into usuario_permiso(idusuario, idpermiso) values ('$idusuario', '$permisos[$zz]')";
			$usr_permiso = ejecutarConsulta($sql_detalle, 'C'); if ($usr_permiso['status'] == false) {  return $usr_permiso; } 
			$zz = $zz + 1;
		}
	

    return $edit_user;		
	}

	//Implementamos un método para desactivar usuario
	public function papelera($idusuario) {
		$sql = "UPDATE usuario set estado='0' where idusuario='$idusuario'";
		$papelera = ejecutarConsulta($sql, 'U');
		//add registro en nuestra bitacora
		$sql_d = $idusuario;
		$sql = "INSERT INTO bitacora_bd(idcodigo,nombre_tabla, id_tabla, sql_d, id_user) VALUES (2,'usuario','$idusuario','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

		return $papelera;
	}

	//Implementamos un método para desactivar usuario
	public function eliminar($idusuario) {
		$sql = "UPDATE usuario set estado_delete='0' where idusuario='$idusuario'";
		$eliminar = ejecutarConsulta($sql, 'U');
		//add registro en nuestra bitacora
		$sql_d = $idusuario;
		$sql = "INSERT INTO bitacora_bd(idcodigo,nombre_tabla, id_tabla, sql_d, id_user) VALUES (4,'usuario','$idusuario','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

		return $eliminar;
	}

	//Implementamos un método para activar usuario
	public function activar($idusuario)	{
		$sql = "UPDATE usuario set estado='1' where idusuario='$idusuario'";
		$activar = ejecutarConsulta($sql, 'U');
		//add registro en nuestra bitacora
		$sql_d = $idusuario;
		$sql = "INSERT INTO bitacora_bd(idcodigo,nombre_tabla, id_tabla, sql_d, id_user) VALUES (1,'usuario','$idusuario','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }

		return $activar;
	}

	//Implementamos un método para activar usuario
	public function cargo_persona($idpersona)	{
		$sql = "SELECT p.idpersona, p.nombre_razonsocial, cp.nombre as cargo_trabajador
		FROM persona as p
		INNER JOIN cargo_trabajador as cp on cp.idcargo_trabajador = p.idcargo_trabajador		
		WHERE p.idpersona = '$idpersona'";
		$datos = ejecutarConsultaSimpleFila($sql);

		if (empty($datos['data'])) {
			$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['idpersona' => '', 'nombre_razonsocial' => '', 'cargo_trabajador' => '' ]  ];
    	return $data;
		}

		return $datos;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario)	{
		$sql = "SELECT u.idusuario, p.idpersona, p.nombre_razonsocial, p.apellidos_nombrecomercial, p.tipo_documento, p.numero_documento, p.celular, p.correo,
		p.foto_perfil, u.login, DATE_FORMAT(u.last_sesion, '%m/%d/%Y %h:%i: %p') AS last_sesion, u.estado,	t.nombre as tipo_persona, c.nombre as cargo_trabajador
		FROM  usuario as u
		inner join persona as p on u.idpersona = p.idpersona
		INNER JOIN tipo_persona as t ON t.idtipo_persona = p.idtipo_persona
		INNER JOIN cargo_trabajador as c ON c.idcargo_trabajador = p.idcargo_trabajador
		where u.idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_clave($idusuario)	{
		$sql = "SELECT u.idusuario, u.idpersona, u.login, u.password, u.estado FROM  usuario as u where u.idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function validar_usuario($idusuario, $user) {
    $validar_user = empty($idusuario) ? "" : "AND u.idusuario != '$idusuario'" ;
    $sql = "SELECT u.idusuario, u.login, u.password, u.estado FROM usuario AS u WHERE u.login = '$user' $validar_user;";
    $buscando =  ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; }

    if (empty($buscando['data'])) { return true; }else { return false; }
  }

	//Implementar un método para listar los registros
	public function listar()	{
		$sql = "SELECT u.idusuario, p.idpersona, p.nombre_razonsocial, p.apellidos_nombrecomercial, sdi.abreviatura as tipo_documento, p.numero_documento, p.celular, 
		p.correo,	p.foto_perfil, u.login, DATE_FORMAT(u.last_sesion, '%d/%m/%Y %h:%i %p') AS last_sesion, u.estado,	t.nombre as tipo_persona, c.nombre as cargo_trabajador
		FROM  usuario as u
		inner join persona as p on u.idpersona = p.idpersona
		INNER JOIN tipo_persona as t ON t.idtipo_persona = p.idtipo_persona
		INNER JOIN cargo_trabajador as c ON c.idcargo_trabajador = p.idcargo_trabajador
		INNER JOIN sunat_c06_doc_identidad as sdi ON sdi.code_sunat = p.tipo_documento
		WHERE u.estado_delete = '1' ORDER BY  u.estado DESC, p.nombre_razonsocial ASC ";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()	{
		$sql = "SELECT * from usuario where condicion=1";
		return ejecutarConsulta($sql);
	}

	//Implementar un metodo para listar los permisos marcados
	public function listarmarcados($idusuario)	{		
		$sql = "SELECT * from usuario_permiso where idusuario='$idusuario'";
		return ejecutarConsultaArray($sql); 		
	}

	public function listar_grupo_marcados($idusuario)	{		
		$sql = "SELECT up.idusuario, p.idpermiso, p.estado, p.modulo, count(p.modulo) 
		from usuario_permiso AS up 
		INNER JOIN permiso as p ON up.idpermiso = p.idpermiso 
		where idusuario='$idusuario'
		GROUP BY p.modulo ORDER BY count(p.modulo) DESC; ";
		return ejecutarConsultaArray($sql); 		
			
	}

	public function listarmarcadosNumeracion($idusuario)	{
		$sql = "SELECT * from sunat_usuario_comprobante where idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Funcion para verificar el acceso al sistema
	public function verificar($login, $clave)	{

		$sql = "SELECT u.idusuario, p.idpersona, pp.idpersonal_pi, cp.idcargo_personal, e.idempresa,
		e.razon_social, s.nombre_sucursal, f.nombre_facultad, c.nombre_carrera,
		p.nombres, p.apellidos, p.tipo_persona, cp.nombre AS cargo,
		p.tipo_documento, p.numero_documento, p.celular, p.correo,
		u.login, p.foto_perfil
		FROM usuario AS u
		INNER JOIN persona AS p ON u.idpersona = p.idpersona
		INNER JOIN personal_del_pi AS pp ON u.idpersona = p.idpersona
		INNER JOIN cargo_personal AS cp ON pp.idcargo_personal = cp.idcargo_personal
		INNER JOIN detalle_usuario_empresa AS due ON u.idusuario = due.idusuario
		INNER JOIN empresa AS e ON due.idempresa = e.idempresa
		INNER JOIN sucursal AS s ON due.idsucursal = s.idsucursal
		LEFT JOIN facultad AS f ON due.idfacultad = f.idfacultad
		LEFT JOIN carrera AS c ON due.idcarrera = c.idcarrera
		WHERE u.login = '$login' AND u.password = '$clave'
		AND u.estado = 1 AND u.estado_delete = 1
		AND p.estado = 1 AND p.estado_delete = 1;";
		$user = ejecutarConsultaSimpleFila($sql); if ($user['status'] == false) {  return $user; } 

		$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['usuario' => $user['data']]  ];
    return $data;
	}

	public function onoffTempo($st)	{
		$sql = "UPDATE temporizador set estado='$st' where id='1' ";
		return ejecutarConsulta($sql);
	}

	public function consultatemporizador()	{
		$sql = "SELECT id as idtempo, tiempo, estado from temporizador where id='1' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function savedetalsesion($idusuario)	{
		$sql = "INSERT into detalle_usuario_sesion (idusuario, tcomprobante, idcomprobante, fechahora) 
      values ('$idusuario', '','', now())";
		return ejecutarConsulta($sql);
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
