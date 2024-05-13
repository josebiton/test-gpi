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
  case 'guardaryeditar':
    
    if (empty($clave)) { #Extraemos la clave antigua     
      $usuario_actual = $usuario->mostrar_clave($idusuario);
      $clavehash = $usuario_actual['data']['password'];
    } else {  # Encriptamos la clave      
      $clavehash = hash("SHA256", $clave);
    }

    if (empty($idusuario)) {
      $rspta = $usuario->insertar($idpersona, $login, $clavehash, $permiso);
      echo json_encode($rspta, true);
    } else {
      $rspta = $usuario->editar($idusuario, $idpersona, $login, $clavehash, $permiso);
      echo json_encode($rspta, true);
    }
  break;

  case 'eliminar':
    $rspta = $usuario->eliminar($_GET["id_tabla"]);
    echo json_encode($rspta, true);
  break;


  case 'activar':
    $rspta = $usuario->activar($_GET["id_tabla"]);
    echo json_encode($rspta, true);
  break;

  case 'cargo_persona':
    $rspta = $usuario->cargo_persona($_POST["idpersona"]);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
  break;

  case 'mostrar':
    $rspta = $usuario->mostrar($idusuario);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
  break;

  case 'validar_usuario':
    $rspta = $usuario->validar_usuario($_GET["idusuario"],$_GET["login"]);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
  break;

  case 'historial_sesion':
    $rspta = $usuario->historial_sesion($_GET["id"]);
    $data = array();
    foreach ($rspta['data'] as $key => $val) {
      $data[] = array(
        "0" => $key +1  ,        
        "1" => $val['last_sesion'],
        "2" => $val['nombre_dia'],
        "3" => $val['nombre_mes'],
      );
    }
    $results = array(
      'status'=> true,
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data),  //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),  //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results, true);
  break;

  case 'listar':
    $rspta = $usuario->listar();
    //Vamos a declarar un array

    $data = array(); $count =1;

    while ($reg = $rspta['data']->fetch_object()) {
      // Mapear el valor numérico a su respectiva descripción      

      $img = empty($reg->foto_perfil) ? 'no-perfil.jpg' : $reg->foto_perfil ;

      $data[] = array(
        "0" => $count++,
        "1" => '<div class="hstack gap-2 fs-15">' .
          '<button class="btn btn-icon btn-sm btn-warning-light" onclick="mostrar(' . $reg->idusuario . ')" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-line"></i></button>'.
          ($reg->estado ? '<button  class="btn btn-icon btn-sm btn-danger-light product-btn" onclick="desactivar(' . $reg->idusuario . ', \'' . encodeCadenaHtml($reg->nombre_razonsocial .' '. $reg->apellidos_nombrecomercial) . '\')" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>':
          '<button class="btn btn-icon btn-sm btn-success-light product-btn" onclick="activar(' . $reg->idusuario . ')" data-bs-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>'
          ).
        '</div>',        
        "2" =>'<div class="d-flex flex-fill align-items-center">
          <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="Ver imagen"><span class="avatar"> <img src="../assets/modulo/persona/perfil/' . $img . '" alt="" onclick="ver_img(\'' . $img . '\', \'' . encodeCadenaHtml($reg->nombre_razonsocial .' '. $reg->apellidos_nombrecomercial) . '\')"> </span></div>
          <div>
            <span class="d-block fw-semibold text-primary">'.$reg->nombre_razonsocial .' '. $reg->apellidos_nombrecomercial.'</span>
            <span class="text-muted">'.$reg->tipo_documento .' '. $reg->numero_documento .' | <i class="ti ti-fingerprint fs-18"></i> '. zero_fill($reg->idusuario, 5).'</span>
          </div>
        </div>',
        "3" => $reg->login,
        "4" => $reg->cargo_trabajador,
        "5" => '<a href="tel:+51'.$reg->celular.'">'.$reg->celular.'</a>',
        "6" => '<span class="cursor-pointer" data-bs-toggle="tooltip" title="Ver historial" onclick="historial_sesion(' . $reg->idusuario . ')" >'.$reg->last_sesion.'</span>',
        "7" => ($reg->estado) ? '<span class="badge bg-success-transparent">Activado</span>' : '<span class="badge bg-danger-transparent">Inhabilitado</span>'
      );
    }
    $results = array(
      'status'=> true,
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data),  //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),  //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);

  break;

  case 'permisos_empresa':
    //Obtenemos todos los permisos de la tabla permisos
    require_once "../modelos/Permiso.php";
    $permiso = new Permiso();
    $rspta = $permiso->listar_permisos_empresa();

    $id = $_GET['id'];
    $marcados = $usuario->listarmarcados($id); # Obtener los permisos asignados al usuario

    $valores = array(); # Declaramos el array para almacenar todos los permisos marcados

    foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']); } # Almacenar los permisos asignados al usuario en el array

    //Mostramos la lista de permisos en la vista y si están o no marcados
    echo '<div class="row gy-2" >';
    foreach ($rspta['data']['agrupado'] as $key => $val1) {   
      echo '<div class="col-lg-4 col-xl-3 col-xxl-3 mt-3" >';
      echo '<span >'.$val1['modulo'].'</span>';
      foreach ($val1['submodulo'] as $key => $val2) {
        $sw = in_array($val2['idpermiso'], $valores) ? 'checked' : '';
        echo '<div class="custom-toggle-switch d-flex align-items-center mt-2 mb-2">
          <input id="permiso_' . $val2['idpermiso'] . '" name="permiso[]" type="checkbox" ' . $sw . ' value="' . $val2['idpermiso'] . '">
          <label for="permiso_' . $val2['idpermiso'] . '" class="label-primary"></label><span class="ms-3">' . $val2['submodulo'] . '</span>
        </div>';
      }  
      echo '</div>';
    }
    echo '</div>';
  break;

  case 'permisos_coordinador':
    //Obtenemos todos los permisos de la tabla permisos
    require_once "../modelos/Permiso.php";
    $permiso = new Permiso();
    $rspta = $permiso->listar_permisos_coordinador();

    $id = $_GET['id'];
    $marcados = $usuario->listarmarcados($id); # Obtener los permisos asignados al usuario

    $valores = array(); # Declaramos el array para almacenar todos los permisos marcados

    foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']); } # Almacenar los permisos asignados al usuario en el array

    //Mostramos la lista de permisos en la vista y si están o no marcados
    echo '<div class="row gy-2" >';
    foreach ($rspta['data']['agrupado'] as $key => $val1) {   
      echo '<div class="col-lg-4 col-xl-3 col-xxl-3 mt-3" >';
      echo '<span >'.$val1['modulo'].'</span>';
      foreach ($val1['submodulo'] as $key => $val2) {
        $sw = in_array($val2['idpermiso'], $valores) ? 'checked' : '';
        echo '<div class="custom-toggle-switch d-flex align-items-center mt-2 mb-2">
          <input id="permiso_' . $val2['idpermiso'] . '" name="permiso[]" type="checkbox" ' . $sw . ' value="' . $val2['idpermiso'] . '">
          <label for="permiso_' . $val2['idpermiso'] . '" class="label-primary"></label><span class="ms-3">' . $val2['submodulo'] . '</span>
        </div>';
      }  
      echo '</div>';
    }
    echo '</div>';
  break;

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
      echo '<span >'.$val1['modulo'].'</span>';
      foreach ($val1['submodulo'] as $key => $val2) {
        $sw = in_array($val2['idpermiso'], $valores) ? 'checked' : '';
        echo '<div class="custom-toggle-switch d-flex align-items-center mt-2 mb-2">
          <input id="permiso_' . $val2['idpermiso'] . '" name="permiso[]" type="checkbox" ' . $sw . ' value="' . $val2['idpermiso'] . '">
          <label for="permiso_' . $val2['idpermiso'] . '" class="label-primary"></label><span class="ms-3">' . $val2['submodulo'] . '</span>
        </div>';
      }  
      echo '</div>';
    }
    echo '</div>';
  break;

// MAS PERMISOS ==========================================================================

  case 'series':
    //Obtenemos todos los permisos de la tabla permisos
    require_once "../modelos/Numeracion.php";
    $numeracion = new Numeracion();
    $rspta = $numeracion->listarSeries();

    //Obtener los permisos asignados al usuario
    $id = $_GET['id'];
    $marcados = $usuario->listarmarcadosNumeracion($id);
    //Declaramos el array para almacenar todos los permisos marcados
    $series_array = array();

    //Almacenar los permisos asignados al usuario en el array
    while ($per = $marcados['data']->fetch_object()) {
      array_push($series_array, $per->idtipo_comprobante);
    }

    //Mostramos la lista de permisos en la vista y si están o no marcados
    echo '<div class="row gy-2" >';
    foreach ($rspta['data'] as $key => $val) {

      if ($key % 3 === 0) {   echo '<div class="col-lg-4 col-xl-3 col-xxl-3" >';   } # abrimos el: col-lg-2      
      
      $sw = in_array($val['idtipo_comprobante'], $series_array) ? 'checked' : '';

      echo '<div class="custom-toggle-switch d-flex align-items-center mb-2 mt-2">
        <input id="serie_' . $val['idtipo_comprobante'] . '" name="serie[]" value="' . $val['idtipo_comprobante'] . '" type="checkbox" ' . $sw . '>
        <label for="serie_' . $val['idtipo_comprobante'] . '" class="label-primary"></label><span class="ms-3">' . $val['abreviatura'] .': <b>'.  $val['serie'] . '-' . $val['numero'] . '</b></span>
      </div>';
      if (($key + 1) % 3 === 0 || $key === count($rspta['data']) - 1) { echo "</div>"; } # cerramos el: col-lg-2
    }
    echo '</div>';
  break;

  case 'seriesnuevo':
    //Obtenemos todos los permisos de la tabla permisos
    require_once "../modelos/Numeracion.php";
    $numeracion = new Numeracion();
    $rspta = $numeracion->listarSeriesNuevo();
    
    while ($reg = $rspta['data']->fetch_object()) { 
      echo '<li> <input type="checkbox" name="serie[]" value="' . $reg->idtipo_comprobante . '">' . $reg->serie . '-' . $reg->numero . ' </li>';
    }
  break;

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
      $_SESSION['idpersona_trabajador'] = $rspta['data']['usuario']['idpersona_trabajador'];
      $_SESSION['user_nombre']    = $rspta['data']['usuario']['nombre_razonsocial'];
      $_SESSION['user_apellido']  = $rspta['data']['usuario']['apellidos_nombrecomercial'];
      $_SESSION['user_tipo_doc']  = $rspta['data']['usuario']['tipo_documento'];
      $_SESSION['user_num_doc']   = $rspta['data']['usuario']['numero_documento'];
      $_SESSION['user_cargo']     = $rspta['data']['usuario']['cargo'];
      $_SESSION['user_imagen']    = $rspta['data']['usuario']['foto_perfil'];
      $_SESSION['user_login']     = $rspta['data']['usuario']['login'];
      $_SESSION['nivel_autoridad']  = $rspta['data']['usuario']['nivel_autoridad'];

      // $_SESSION['idusuario_empresa']  = $rspta['data']['sucursal']['idusuario_empresa'];
      // $_SESSION['idempresa']          = $rspta['data']['sucursal']['idempresa'];
      // $_SESSION['empresa_nrs']        = $rspta['data']['sucursal']['nombre_razon_social'];    
      // $_SESSION['empresa_nc']         = $rspta['data']['sucursal']['nombre_comercial'];
      // $_SESSION['empresa_ruc']        = $rspta['data']['sucursal']['numero_ruc'];     
      // $_SESSION['empresa_domicilio']  = $rspta['data']['sucursal']['domicilio_fiscal'];        
      // $_SESSION['empresa_iva']        = $rspta['data']['sucursal']['igv'];

      // $_SESSION['estadotempo']        = $rspta3['data']['estado'];      
      
      $marcados = $usuario->listarmarcados($rspta['data']['usuario']['idusuario']);         # Obtenemos los permisos del usuario
      $grupo    = $usuario->listar_grupo_marcados($rspta['data']['usuario']['idusuario']);  # Obtenemos los permisos del usuario
      // $usuario->savedetalsesion($rspta['data']['usuario']['idusuario']);                 # Guardamos los datos del usuario al iniciar sesion.

      $valores = array();           # Declaramos el array para almacenar todos los permisos marcados
      $valores_agrupado = array();  # Declaramos el array para almacenar todos los permisos marcados

      foreach ($marcados['data'] as $key => $val) { array_push($valores, $val['idpermiso']);  } # Almacenamos los permisos marcados en el array      
      
      foreach ($grupo['data'] as $key => $val) { array_push($valores_agrupado, $val['modulo']);  }  # Almacenamos los permisos marcados en el array
               
      in_array(1, $valores) ? $_SESSION['dashboard_empresa']        = 1 : $_SESSION['dashboard_empresa']        = 0;
      in_array(2, $valores) ? $_SESSION['empresa']                  = 1 : $_SESSION['empresa']                  = 0;
      in_array(3, $valores) ? $_SESSION['nosotros']                 = 1 : $_SESSION['nosotros']                 = 0;
      in_array(4, $valores) ? $_SESSION['sucursales']               = 1 : $_SESSION['sucursales']               = 0;
      in_array(5, $valores) ? $_SESSION['departamentos']            = 1 : $_SESSION['departamentos']            = 0;
      in_array(6, $valores) ? $_SESSION['subdepartamentos']         = 1 : $_SESSION['subdepartamentos']         = 0;
      in_array(7, $valores) ? $_SESSION['departamentos_operativos'] = 1 : $_SESSION['departamentos_operativos'] = 0;
      in_array(8, $valores) ? $_SESSION['usuario']                  = 1 : $_SESSION['usuario']                  = 0;
      in_array(8, $valores) ? $_SESSION['trabajador']               = 1 : $_SESSION['trabajador']               = 0;
      

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
