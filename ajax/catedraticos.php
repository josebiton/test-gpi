<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['Catedraticos'] == 1) {
    
    require_once "../modelos/Catedraticos.php";

    $catedraticos = new Catedraticos();
    
    date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
    $toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';
    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/gpi/admin/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    // :::::::::::::::::::::::::::::::::::: D A T O S   E M P R E S A ::::::::::::::::::::::::::::::::::::::

    $idpersona          = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
    $tipo_persona       = isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
    $idpersonal_pi      = isset($_POST["idpersonal_pi"])? limpiarCadena($_POST["idpersonal_pi"]):"";

    $tipo_documento     = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
    $numero_documento   = isset($_POST["numero_documento"])? limpiarCadena($_POST["numero_documento"]):"";
    $idcargo_personal   = isset($_POST["idcargo_personal"])? limpiarCadena($_POST["idcargo_personal"]):"";
    $nombres            = isset($_POST["nombres"])? limpiarCadena($_POST["nombres"]):"";
    $apellidos          = isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
    $direccion          = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
    $correo             = isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";
    $celular            = isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
      

    switch ($_GET["op"]) {   
      
      // :::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R   ::::::::::::::::::::::::::

      case 'guardar_y_editar':
        //guardar f_img_fondo fondo
        if ( !file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']) ) {
          $img_perfil = $_POST["imagenactual"];
          $flat_img1 = false; 
        } else {          
          $ext1 = explode(".", $_FILES["imagen"]["name"]);
          $flat_img1 = true;
          $img_perfil = $date_now . '__' . random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/modulo/persona/perfil/" . $img_perfil);          
        }        

        if ( empty($idpersona) ) { #Creamos el registro

          $rspta = $catedraticos->insertar( $tipo_persona, $tipo_documento, $numero_documento, $idcargo_personal, $nombres, $apellidos, $direccion, $correo, $celular, $img_perfil );
          echo json_encode($rspta, true);

        } else { # Editamos el registro

          if ($flat_img1 == true || empty($img_perfil)) {
            $datos_f1 = $catedraticos->perfil_docente($idpersona);
            $img1_ant = $datos_f1['data']['foto_perfil'];
            if (!empty($img1_ant)) { unlink("../assets/modulo/persona/perfil/" . $img1_ant); }         
          }  
         
          $rspta = $catedraticos->editar($idpersona, $tipo_persona, $idpersonal_pi, $tipo_documento, $numero_documento, $idcargo_personal, $nombres, $apellidos, $direccion, $correo, $celular, $img_perfil);
          echo json_encode($rspta, true);
        }        

        
      break;      

      case 'listar_tabla_principal':
        $rspta = $catedraticos->listar_tabla_principal();
        
        $data = array(); $count =1;
    
        $toltip = '<script> $(function() { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status'] == true) {

          foreach ($rspta['data'] as $key => $value) {

            $img = empty($value['foto_perfil']) ? 'no-perfil.jpg' : $value['foto_perfil'];

            $contacto = !empty($value['celular']) && !empty($value['correo']) 
              ? '<b>Celular: </b><span class="badge bg-secondary-transparent">'.$value['celular'].'</span><br><b>Correo: </b><span class="badge bg-secondary-transparent">'.$value['correo'].'</span>' 
              : (!empty($value['celular']) 
                ? '<b>Celular: </b><span class="badge bg-secondary-transparent">'.$value['celular'].'</span>' 
                : (!empty($value['correo']) 
                  ? '<b>Correo: </b><span class="badge bg-secondary-transparent">'.$value['correo'].'</span>' 
                  : '<span class="badge bg-danger-transparent">Sin contacto</span>'));

            $data[] = array(
              "0" => $count++,
              "1" =>  '<div class="hstack gap-2 fs-15">' .
                        '<button class="btn btn-icon btn-sm btn-warning-light" onclick="mostrar(' . $value['idpersona'] . ')" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-line"></i></button>'.
                        ($value['estado'] ? '<button  class="btn btn-icon btn-sm btn-danger-light product-btn" onclick="desactivar(' . $value['idpersonal_pi']. ', '. $value['idpersona'] . ', \'' . encodeCadenaHtml($value['nombres'] .' '. $value['apellidos']) . '\')" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>':
                        '<button class="btn btn-icon btn-sm btn-success-light product-btn" onclick="activar(' . $value['idpersonal_pi']. ', '. $value['idpersona'] . ')" data-bs-toggle="tooltip" title="Activar"><i class="fa fa-check"></i></button>'
                        ).
                      '</div>',
              "2" =>  '<div class="d-flex flex-fill align-items-center">
                        <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="Ver imagen"><span class="avatar"> <img src="../assets/modulo/persona/perfil/' . $img . '" alt="" onclick="ver_img(\'' . $img . '\', \'' . encodeCadenaHtml($value['nombres'] .' '. $value['apellidos']) . '\')"> </span></div>
                        <div>
                          <span class="d-block fw-semibold text-primary">'.$value['nombres'] .' '. $value['apellidos'].'</span>
                          <span class="text-muted"><b>'.$value['tipo_documento'] .'</b>: '. $value['numero_documento'] .' | <i class="ti ti-fingerprint fs-18"></i> '. zero_fill($value['idpersonal_pi'], 5).'</span>
                        </div>
                      </div>',
              "3" =>  '<span class="badge bg-success-transparent">'.$value['cargo'].'</span>',
              "4" =>  $contacto,
              "5" =>  (!empty($value['direccion'])) ? '<span class="badge bg-secondary-transparent">'.$value['direccion'].'</span>' : '<span class="badge bg-danger-transparent">Sin dirección</span>',
              "6" =>  ($value['estado'] == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Desactivado</span>'
            );
          }
          $results = [
            'status'=> true,
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] . ' - ' . $rspta['message'] . ' ' . $rspta['data'];
        }
    
      break;

      case 'mostrar_trabajador':
        $rspta = $catedraticos->mostrar_personal($_POST["idpersona"]);
        echo json_encode($rspta);
      break;    
      
      case 'eliminar':
        $rspta = $catedraticos->eliminar($_GET["id_tabla"], $_GET["idpersona"]);
        echo json_encode($rspta, true);
      break;

      case 'papelera':
        $rspta = $catedraticos->papelera($_GET["id_tabla"], $_GET["idpersona"]);
        echo json_encode($rspta, true);
      break;

      case 'activar':
        $rspta = $catedraticos->activar($_GET["id_tabla"], $_GET["idpersona"]);
        echo json_encode($rspta, true);
      break;
    

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }

  } else {
    $retorno = ['status'=>'nopermiso', 'message'=>'No tienes acceso a este modulo, pide acceso a tu administrador', 'data' => [], 'aaData' => [] ];
    echo json_encode($retorno);
  }  
}

ob_end_flush();
?>
