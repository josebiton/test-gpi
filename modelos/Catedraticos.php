<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Catedraticos
{
	//Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
  }

	//Implementar un método para listar los registros
	public function listar_tabla_principal()	{
		$sql = "SELECT p.idpersona, pp.idpersonal_pi, p.nombres, p.apellidos, p.tipo_documento, p.numero_documento, cp.nombre AS cargo,
		p.celular, p.correo, p.direccion, p.foto_perfil, pp.estado
		FROM persona AS p
		INNER JOIN personal_del_pi AS pp ON pp.idpersona = p.idpersona
		INNER JOIN cargo_personal AS cp ON pp.idcargo_personal = cp.idcargo_personal
		WHERE pp.idcargo_personal = '2' AND pp.estado = 1 AND pp.estado_delete = 1;";
		return ejecutarConsulta($sql);
	}


	//Implementamos un método para insertar registros
	public function insertar($tipo_persona, $tipo_documento, $numero_documento, $idcargo_personal, $nombres, $apellidos, $direccion, $correo, $celular, $img_perfil)	{

		//los datos ya existes?
		$sql_0 = "SELECT p.tipo_documento, p.numero_documento, p.nombres, p.apellidos, cp.nombre AS cargo, p.estado, p.estado_delete
		FROM persona AS p 
		INNER JOIN personal_del_pi AS pp ON p.idpersona = pp.idpersona
		INNER JOIN cargo_personal AS cp ON pp.idcargo_personal = cp.idcargo_personal
		WHERE p.tipo_documento = '$tipo_documento' AND p.numero_documento = '$numero_documento'";
    $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
    if ( empty($existe['data']) ) {
			//Registramos a la persona
			$sql = "INSERT INTO persona(tipo_persona, nombres, apellidos, tipo_documento, numero_documento, celular, direccion, correo, foto_perfil) 
			VALUES('$tipo_persona', '$nombres', '$apellidos', '$tipo_documento', '$numero_documento', '$celular', '$direccion', '$correo', '$img_perfil')";
			$id_new = ejecutarConsulta_retornarID($sql, 'C');	if ($id_new['status'] == false) {  return $id_new; } 		

			$id = $id_new['data'];

			// Registramos a la persona como trabajador
			$sql_detalle = "INSERT INTO personal_del_pi (idpersona, idcargo_personal) VALUES ('$id', '$idcargo_personal')";
			$usr_permiso = ejecutarConsulta($sql_detalle, 'C'); if ($usr_permiso['status'] == false) {  return $usr_permiso; }		

			return $id_new;
		} else {
			$info_repetida = ''; 

			// La persona ya existe
			foreach ($existe['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
					<span class="font-size-15px text-danger"><b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'</span><br>
					<b>Nombre: </b>'.$value['nombres'].' '.$value['apellidos'].'<br>
					<b>Cargo: </b>'.$value['cargo'].'<br>
					<b>Visible: </b>'.( $value['estado']==1 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
					<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
					<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
					<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
		}			
	}
	//Implementamos un método para editar registros
	public function editar() {

		$sql_0 = "";
    $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
    if ( empty($existe['data']) ) {
			$sql = "";
			$edit_user = ejecutarConsulta($sql, 'U'); if ($edit_user['status'] == false) {  return $edit_user; }

			$sql_detalle = "";
			$usr_permiso = ejecutarConsulta($sql_detalle, 'U'); if ($usr_permiso['status'] == false) {  return $usr_permiso; }

			return $edit_user;	
		} else {
			$info_repetida = ''; 

			foreach ($existe['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
					<span class="font-size-15px text-danger"><b>'.$value['nombre_tipo_documento'].': </b>'.$value['numero_documento'].'</span><br>
					<b>Nombre: </b>'.$value['nombre_razonsocial'].' '.$value['apellidos_nombrecomercial'].'<br>
					<b>Cargo: </b>'.$value['cargo'].'<br>
					<b>Sueldo: </b>'.$value['sueldo_mensual'].'<br>
					<b>Fecha Nac.: </b>'.$value['fecha_nacimiento'].'<br>
					<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
					<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
					<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
		}			
	}









	public function eliminar($idpersonal_pi, $idpersona) {
		$sql = "UPDATE persona SET estado_delete='0' WHERE idpersona='$idpersona'";	ejecutarConsulta($sql);
		$sql = "UPDATE personal_del_pi SET estado_delete='0' WHERE idpersonal_pi='$idpersonal_pi'";
		$eliminar = ejecutarConsulta($sql, 'D'); if ($eliminar['status'] == false) {  return $eliminar; }
		return $eliminar;
	}

	public function papelera($idpersonal_pi, $idpersona) {
		$sql = "UPDATE persona SET estado='0' WHERE idpersona='$idpersona'";	ejecutarConsulta($sql);
		$sql = "UPDATE personal_del_pi SET estado='0' WHERE idpersonal_pi='$idpersonal_pi'";
		$papelera = ejecutarConsulta($sql, 'T'); if ($papelera['status'] == false) {  return $papelera; }
		return $papelera;
	}

	public function activar($idpersonal_pi, $idpersona)	{
		$sql = "UPDATE persona SET estado='0' WHERE idpersona='$idpersona'";	ejecutarConsulta($sql);
		$sql = "UPDATE personal_del_pi SET estado='1' WHERE idpersonal_pi='$idpersonal_pi'";
		$activar = ejecutarConsulta($sql, 'U');  if ($activar['status'] == false) {  return $activar; }
		return $activar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_personal($idpersona)	{
		$sql = "SELECT p.*, pt.idpersona_trabajador, pt.ruc, pt.usuario_sol, pt.clave_sol, pt.sueldo_mensual, pt.sueldo_diario, t.nombre as tipo_persona, c.nombre as cargo_trabajador, 
		sdi.abreviatura as tipo_documento, sdi.code_sunat, pt.idpersona_trabajador		
		FROM  persona as p
		inner join persona_trabajador as pt on pt.idpersona = p.idpersona
		INNER JOIN tipo_persona as t ON t.idtipo_persona = p.idtipo_persona
		INNER JOIN cargo_trabajador as c ON c.idcargo_trabajador = p.idcargo_trabajador
		INNER JOIN sunat_c06_doc_identidad as sdi ON sdi.code_sunat = p.tipo_documento
		WHERE p.idpersona='$idpersona' AND p.estado = '1' AND p.estado_delete = '1';";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function perfil_docente($id)	{
		$sql = "SELECT p.foto_perfil	FROM persona as p WHERE p.idpersona = '$id' ;";
		return ejecutarConsultaSimpleFila($sql);
	}

}
