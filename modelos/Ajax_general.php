<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Ajax_general
{
	//Implementamos nuestro variable global
  public $id_usr_sesion;

  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  } 

  // ══════════════════════════════════════ RENIEC gpi ══════════════════════════════════════
  public function datos_reniec_gpi($dni) { 

    $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    
    $curl = curl_init();                              //  Iniciamos curl    
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );  // Desactivamos verificación SSL    
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  // Devuelve respuesta aunque sea falsa    
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );// Especificamo los MIME-Type que son aceptables para la respuesta.    
    curl_setopt( $curl, CURLOPT_URL, $url );          // Establecemos la URL    
    $json = curl_exec( $curl );                       // Ejecutmos curl    
    curl_close( $curl );                              // Cerramos curl
    return json_decode( $json, true );
  }

  // ══════════════════════════════════════ RENIEC WFACX ══════════════════════════════════════
  public function datos_reniec_otro($ruc)	{ 
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
    $nndnii = $_GET['nrodni'];

    // Iniciar llamada a API
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $nndnii,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 2,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array( 'Referer: https://apis.net.pe/consulta-dni-api', 'Authorization: Bearer' . $token ),
    ));
    $response = curl_exec($curl); // Ejecutmos curl 
    curl_close($curl);            // Cerramos curl
    
    return json_decode($response);
  }

  // ══════════════════════════════════════ SUNAT gpi ══════════════════════════════════════
  public function datos_sunat_gpi($ruc)	{ 
    $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";    
    $curl = curl_init();                              //  Iniciamos curl    
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );  // Desactivamos verificación SSL    
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  // Devuelve respuesta aunque sea falsa    
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );// Especificamo los MIME-Type que son aceptables para la respuesta.    
    curl_setopt( $curl, CURLOPT_URL, $url );          // Establecemos la URL    
    $json = curl_exec( $curl );                       // Ejecutmos curl    
    curl_close( $curl );                              // Cerramos curl
    return json_decode( $json, true );
  }  
  

  // ══════════════════════════════════════ SUNAT WFACX ══════════════════════════════════════
  public function datos_sunat_otro($ruc)	{ 
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';  

    // Iniciar llamada a API
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Referer: https://apis.net.pe/api-ruc',
        'Authorization: Bearer' . $token
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    // Datos listos para usar
    return json_decode($response);
  }

  /* ══════════════════════════════════════ S U N A T   ══════════════════════════════════════ */

  public function select2_tipo_documento()	{
    // $data = [];
		$sql="SELECT * FROM sunat_c06_doc_identidad";
		return ejecutarConsultaArray($sql);   
	}

  // ══════════════════════════════════════ U S U A R I O - S E L E C T 2  ══════════════════════════════════════
	public function select2_usuario_trabajador()	{
    $user = $_SESSION['idusuario'];

		$sql="SELECT p.idpersona, pt.idpersona_trabajador, p.foto_perfil, ct.nombre cargo, p.numero_documento,
    CASE 
      WHEN p.tipo_persona_sunat = 'NATURAL' THEN CONCAT(p.nombre_razonsocial, ' ', p.apellidos_nombrecomercial) 
        WHEN p.tipo_persona_sunat = 'JURÍDICA' THEN p.nombre_razonsocial 
      ELSE '-'
    END AS nombre_completo
    FROM persona_trabajador pt
    INNER JOIN persona AS p ON pt.idpersona = p.idpersona
    INNER JOIN cargo_trabajador AS ct ON p.idcargo_trabajador = ct.idcargo_trabajador
    LEFT JOIN usuario AS u ON p.idpersona = u.idpersona
    WHERE u.idusuario <> '$user' OR u.idusuario IS NULL";
		$select_1 = ejecutarConsultaArray($sql);

    return $retorno = ['status'=>true, 'mesage'=>'Todo bien', 'data'=>$select_1['data'], ]; 
	}

  // ══════════════════════════════════════ U S U A R I O - S E L E C T 2  ══════════════════════════════════════
	public function select2_cargo()	{
    // $data = [];
		$sql="SELECT * FROM cargo_trabajador WHERE estado='1' AND estado_delete='1'";
		return ejecutarConsultaArray($sql);   
	}

  // ══════════════════════════════════════ B A N C O - S E L E C T 2  ══════════════════════════════════════
	public function select2_banco()	{
    // $data = [];
		$sql="SELECT * FROM bancos WHERE estado='1' AND estado_delete = '1'";
		return ejecutarConsultaArray($sql);   
	}

  // ══════════════════════════════════════ P R O D U C T O  ══════════════════════════════════════

  public function create_code_producto($pre_codigo)	{
    
		// Consulta para obtener el último código que comienza con 'PR' de la tabla: where codigo like 'PR%'
    $sql = "SELECT max(idproducto) as last_code from producto";
    $result = ejecutarConsultaSimpleFila($sql);
    
    $last_code = $result['data']['last_code'];

    if ($last_code == NULL) {
      $new_num = 1;                               # SE INICIA EN EL NUMERO 1 SI NO HAY REGISTROS
    } else {      
      // $num_part = (int) substr($last_code, 2); # RECORTE EL PRE NOMBRE      
      $new_num  = $last_code + 1;                 # AUMENTAMOS +1
    }

    $new_code = $pre_codigo.str_pad($new_num, 5, "0", STR_PAD_LEFT); # CREAMOS EL CODIGO

    return ['status' => true,  'message' => 'Salió todo ok',  'data' => ['nombre_codigo' => $new_code, 'pre_codigo' => $pre_codigo, 'numeracion' => $new_num] ] ; 
	}

}
