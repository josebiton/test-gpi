<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	public function listar() {
		$sql="SELECT * from permiso where not idpermiso in('6','7') ";
		return ejecutarConsulta($sql);		
	}

	public function listar_permisos_empresa() {

		$data_permiso = [];
		$sql_1 = "SELECT * from permiso WHERE nivel_de_autoridad = 'Nivel_1'";	$todos = ejecutarConsultaArray($sql_1); 
		$sql_2 = "SELECT idpermiso, estado, modulo, count(modulo) from permiso WHERE nivel_de_autoridad = 'Nivel_1' GROUP BY modulo ORDER BY count(modulo) DESC";	$agrupado = ejecutarConsultaArray($sql_2);

		foreach ($agrupado['data'] as $key => $val) {

			$modulo = $val['modulo'];
			$sql = "SELECT * from permiso where modulo = '$modulo' AND nivel_de_autoridad = 'Nivel_1'";	$agrupado = ejecutarConsultaArray($sql);

			$data_permiso[] = [
				'idpermiso'	=> $val['idpermiso'],
				'modulo'		=> $val['modulo'],
				'estado'		=> $val['estado'],	
				'submodulo'	=> $agrupado['data']			
			];
		}
		$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['todos' => $todos['data'], 'agrupado' => $data_permiso ]  ];
    return $data; 	
	}

	public function listar_permisos_coordinador() {

		$data_permiso = [];
		$sql = "SELECT * from permiso WHERE nivel_de_autoridad = 'Nivel_2'";	$todos = ejecutarConsultaArray($sql); 
		$sql = "SELECT idpermiso, estado, modulo, count(modulo) from permiso WHERE nivel_de_autoridad = 'Nivel_2' GROUP BY modulo ORDER BY count(modulo) DESC";	$agrupado = ejecutarConsultaArray($sql);

		foreach ($agrupado['data'] as $key => $val) {

			$modulo = $val['modulo'];
			$sql = "SELECT * from permiso where modulo = '$modulo' AND nivel_de_autoridad = 'Nivel_2'";	$agrupado = ejecutarConsultaArray($sql);

			$data_permiso[] = [
				'idpermiso'	=> $val['idpermiso'],
				'modulo'		=> $val['modulo'],
				'estado'		=> $val['estado'],	
				'submodulo'	=> $agrupado['data']			
			];
		}
		$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['todos' => $todos['data'], 'agrupado' => $data_permiso ]  ];
    return $data; 	
	}

	public function listar_permisos_docente() {

		$data_permiso = [];
		$sql = "SELECT * from permiso WHERE nivel_de_autoridad = 'Nivel_3'";	$todos = ejecutarConsultaArray($sql); 
		$sql = "SELECT idpermiso, estado, modulo, count(modulo) from permiso WHERE nivel_de_autoridad = 'Nivel_3' GROUP BY modulo ORDER BY count(modulo) DESC";	$agrupado = ejecutarConsultaArray($sql);

		foreach ($agrupado['data'] as $key => $val) {

			$modulo = $val['modulo'];
			$sql = "SELECT * from permiso where modulo = '$modulo' AND nivel_de_autoridad = 'Nivel_3'";	$agrupado = ejecutarConsultaArray($sql);

			$data_permiso[] = [
				'idpermiso'	=> $val['idpermiso'],
				'modulo'		=> $val['modulo'],
				'estado'		=> $val['estado'],	
				'submodulo'	=> $agrupado['data']			
			];
		}
		$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['todos' => $todos['data'], 'agrupado' => $data_permiso ]  ];
    return $data; 	
	}

	public function listar_permisos_estudiante() {

		$data_permiso = [];
		$sql = "SELECT * from permiso WHERE nivel_de_autoridad = 'Nivel_4'";	$todos = ejecutarConsultaArray($sql); 
		$sql = "SELECT idpermiso, estado, modulo, count(modulo) from permiso WHERE nivel_de_autoridad = 'Nivel_4' GROUP BY modulo ORDER BY count(modulo) DESC";	$agrupado = ejecutarConsultaArray($sql);

		foreach ($agrupado['data'] as $key => $val) {

			$modulo = $val['modulo'];
			$sql = "SELECT * from permiso where modulo = '$modulo' AND nivel_de_autoridad = 'Nivel_4'";	$agrupado = ejecutarConsultaArray($sql);

			$data_permiso[] = [
				'idpermiso'	=> $val['idpermiso'],
				'modulo'		=> $val['modulo'],
				'estado'		=> $val['estado'],	
				'submodulo'	=> $agrupado['data']			
			];
		}
		$data = [ 'status'=>true, 'message'=>'todo okey','data'=> ['todos' => $todos['data'], 'agrupado' => $data_permiso ]  ];
    return $data; 	
	}

	public function listarEmpresa()	{
		$sql="SELECT * from empresa";
		return ejecutarConsultaArray($sql);		
	}
	
}

?>