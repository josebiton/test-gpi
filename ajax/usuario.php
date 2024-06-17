<?php
ob_start();

if (strlen(session_id()) < 1) { session_start(); } //Validamos si existe o no la sesión

require_once "../modelos/Usuario.php";
$usuario = new Usuario();

date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
$imagen_error = "this.src='../dist/svg/404-v2.svg'";
$toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

# ══════════════════════════════════════ D A T O S   U S U A R I O ══════════════════════════════════════ 
$idusuario  = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$idpersona  = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
$login      = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave      = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";

$permiso    = isset($_POST["permiso"]) ? $_POST['permiso'] : "";

switch ($_GET["op"]) {

  case 'validar_usuario':
    $rspta = $usuario->validar_usuario($_GET["idusuario"],$_GET["login"]);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
  break;

  case 'verificar':

    $logina   = $_POST['logina'];
    $clavea   = $_POST['clavea'];
    $st       = $_POST['st'];
    $clavehash = hash("SHA256", $clavea);

    $rspta  = $usuario->verificar($logina, $clavehash);

    if (!empty($rspta['data']['usuario'])) {

      
      $rspta2 = $usuario->last_sesion($rspta['data']['usuario']['idusuario']); # Ultima sesion
      
      //Declaramos las variables de sesión
      $_SESSION['idusuario']      = $rspta['data']['usuario']['idusuario'];
      $_SESSION['idpersona']      = $rspta['data']['usuario']['idpersona'];
      $_SESSION['user_nombre']    = $rspta['data']['usuario']['nombres'];
      $_SESSION['user_apellido']  = $rspta['data']['usuario']['apellidos'];
      $_SESSION['tipo_persona']   = $rspta['data']['usuario']['tipo_persona'];
      $_SESSION['user_num_doc']   = $rspta['data']['usuario']['numero_documento'];
      $_SESSION['user_imagen']    = $rspta['data']['usuario']['foto_perfil'];
      //Filtros
      $_SESSION['filtro_user_a']  = $rspta['data']['filtro_user']['filtro_a'];
      $_SESSION['filtro_user_b']  = $rspta['data']['filtro_user']['filtro_b'];
      $_SESSION['filtro_user_c']  = $rspta['data']['filtro_user']['filtro_c'];

      $marcados = $usuario->listarmarcados($rspta['data']['usuario']['idusuario']);         # Obtenemos los permisos del usuario

      $valores = array();           # Declaramos el array para almacenar todos los permisos marcados

      foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']);  } # Almacenamos los permisos marcados en el array      
      
               
      in_array(1, $valores) ? $_SESSION['escritorioE']  = 1 : $_SESSION['escritorioE']      = 0;
      in_array(2, $valores) ? $_SESSION['perfil']       = 1 : $_SESSION['perfil']           = 0;
      in_array(3, $valores) ? $_SESSION['cronograma']   = 1 : $_SESSION['cronograma']       = 0;
      in_array(4, $valores) ? $_SESSION['equipo']       = 1 : $_SESSION['equipo']           = 0;
      in_array(5, $valores) ? $_SESSION['usuario']      = 1 : $_SESSION['usuario']          = 0;
      in_array(6, $valores) ? $_SESSION['usuario estudiante'] = 1 : $_SESSION['usuario estudiante'] = 0;



      $data = [ 'status'=>true, 'message'=>'todo okey','data'=> $rspta['data']  ];
      echo json_encode($data, true);
    }else{
      $data = [ 'status'=>true, 'message'=>'todo okey','data'=>[]   ];
      echo json_encode($data, true);
    }
    
  break;

  case 'salir':     
    session_unset();  //Limpiamos las variables de sesión  
    session_destroy(); //Destruìmos la sesión
    // header("Location: ../index.php"); 
    header("Location: index.php?file=".(isset($_GET["file"]) ? $_GET["file"] : "")); //Redireccionamos al login
  break;    
}

ob_end_flush();
