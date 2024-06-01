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
  

  case 'permisos_docente':
    //Obtenemos todos los permisos de la tabla permisos
    require_once "../modelos/Permiso.php";
    $permiso = new Permiso();
    $rspta = $permiso->listar_permisos_docente();

    $id = $_GET['id'];
    $marcados = $usuario->listarmarcados($id); # Obtener los permisos asignados al usuario

    $valores = array(); # Declaramos el array para almacenar todos los permisos marcados

    foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']); } # Almacenar los permisos asignados al usuario en el array

    //Mostramos la lista de permisos en la vista y si están o no marcados
    echo '<div class="row gy-2" >';
    foreach ($rspta['data']['agrupado'] as $key => $val1) {   
      echo '<div class="col-lg-4 col-xl-3 col-xxl-3 mt-3" >';
      echo '<span ><b>'.$val1['modulo'].'</b></span>';
      foreach ($val1['submodulo'] as $key => $val2) {
        $sw = in_array($val2['idpermiso'], $valores) ? 'checked' : '';
        echo '<div class="custom-toggle-switch d-flex align-items-center mt-2 mb-2">
          <input id="permiso_' . $val2['idpermiso'] . '" name="permiso_d[]" type="checkbox" ' . $sw . ' value="' . $val2['idpermiso'] . '" checked>
          <label for="permiso_' . $val2['idpermiso'] . '" class="label-primary"></label><span class="ms-3">' . $val2['submodulo'] . '</span>
        </div>';
      }  
      echo '</div>';
    }
    echo '</div>';
  break;

  case 'validar_usuario':
    $rspta = $usuario->validar_usuario($_GET["idusuario"],$_GET["login"]);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
  break;
  

  // MAS PERMISOS ==========================================================================

  case 'verificar':

    $logina   = $_POST['logina'];
    $clavea   = $_POST['clavea'];
    $st       = $_POST['st'];

    //Hash SHA256 en la contraseña
    //$clavehash=$clavea;
    $clavehash = hash("SHA256", $clavea);

    $rspta  = $usuario->verificar($logina, $clavehash);    
    // $rspta2 = $usuario->onoffTempo($st);
    // $rspta3 = $usuario->consultatemporizador();    

    if (!empty($rspta['data']['usuario'])) {

      
      $rspta2 = $usuario->last_sesion($rspta['data']['usuario']['idusuario']); # Ultima sesion
      
      //Declaramos las variables de sesión
      $_SESSION['idusuario']      = $rspta['data']['usuario']['idusuario'];
      $_SESSION['idpersona']      = $rspta['data']['usuario']['idpersona'];
      $_SESSION['tipo_persona']   = $rspta['data']['usuario']['tipo_persona'];
      $_SESSION['user_nombre']    = $rspta['data']['usuario']['nombres'];
      $_SESSION['user_apellido']  = $rspta['data']['usuario']['apellidos'];
      $_SESSION['user_tipo_doc']  = $rspta['data']['usuario']['tipo_documento'];
      $_SESSION['user_num_doc']   = $rspta['data']['usuario']['numero_documento'];
      $_SESSION['user_imagen']    = $rspta['data']['usuario']['foto_perfil'];
      $_SESSION['user_login']     = $rspta['data']['usuario']['login'];

      //Declaramos las variables de empresa
      $_SESSION['idempresa']      = $rspta['data']['usuario']['idempresa'];
      $_SESSION['razon_social']   = $rspta['data']['usuario']['razon_social'];
      $_SESSION['sucursal']       = $rspta['data']['usuario']['nombre_sucursal'];
      $_SESSION['facultad']       = $rspta['data']['usuario']['nombre_facultad'];
      $_SESSION['carrera']        = $rspta['data']['usuario']['nombre_carrera'];

      
      
      $marcados = $usuario->listarmarcados($rspta['data']['usuario']['idusuario']);         # Obtenemos los permisos del usuario

      $valores = array();           # Declaramos el array para almacenar todos los permisos marcados

      foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']);  } # Almacenamos los permisos marcados en el array      
      
               
      in_array(1, $valores) ? $_SESSION['Dashboard PI']       = 1 : $_SESSION['Dashboard PI']       = 0;
      in_array(2, $valores) ? $_SESSION['Mi Perfil']          = 1 : $_SESSION['Mi Perfil']          = 0;
      in_array(3, $valores) ? $_SESSION['Docentes']           = 1 : $_SESSION['Docentes']           = 0;
      in_array(4, $valores) ? $_SESSION['Cursos']             = 1 : $_SESSION['Cursos']             = 0;

      

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
