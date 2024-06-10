<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['perfil'] == 1) {

    require_once "../modelos/Perfil_proyecto.php";
    $Perfil_proyecto = new Perfil_proyecto();

    $titulo_p       = isset($_POST["titulo_p"])? limpiarCadena($_POST["titulo_p"]):"";
    $descripcion_p  = isset($_POST["descripcion_p"])? limpiarCadena($_POST["descripcion_p"]):"";
    $fecha_i        = isset($_POST["fecha_i"])? limpiarCadena($_POST["fecha_i"]):"";
    $fecha_e        = isset($_POST["fecha_e"])? limpiarCadena($_POST["fecha_e"]):"";
    $link_p         = isset($_POST["link_p"])? limpiarCadena($_POST["link_p"]):"";

    $idhitos         = isset($_POST["idhitos"])? limpiarCadena($_POST["idhitos"]):"";
    $idperfil_pi         = isset($_POST["idperfil_pi"])? limpiarCadena($_POST["idperfil_pi"]):"";
    $nombre_hito         = isset($_POST["nombre_hito"])? limpiarCadena($_POST["nombre_hito"]):"";
    $fecha_hito_e         = isset($_POST["fecha_hito_e"])? limpiarCadena($_POST["fecha_hito_e"]):"";
    $descr_hito         = isset($_POST["descr_hito"])? limpiarCadena($_POST["descr_hito"]):"";


    switch ($_GET["op"]) {

      case 'mostrar_perfil_p':
        $rspta = $Perfil_proyecto->mostrar_perfil_p($_POST["idequipo"]);
        echo json_encode($rspta);
      break;

      case 'tabl_hitos':
        $rspta = $Perfil_proyecto->lista_hitos($_GET["idequipo"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          foreach($rspta['data'] as $key => $value){

            $data[]=[
              "0" => $count++,
              "1" => $value['titulo_hito'],
              "2" => $value['descripcion'],
              "3" => $value['fecha_entrega'],
              "4" => ($value['estado_activo'] == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Desactivado</span>'
            ];

          }
          $results =[
            'status'=> true,
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
          ];
          echo json_encode($results);

        } else { echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data']; }
      break;

      case 'listar_tabla_hitos':

        $rspta = $Perfil_proyecto->lista_hitos($_GET["idequipo"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          foreach($rspta['data'] as $key => $value){

            $data[]=[
              "0" => $count++,
              "1" => '<button class="btn btn-icon btn-sm btn-warning-light" onclick="mostrar_hito(' . $value['idhitos'] . ')" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-line"></i></button>'.
                ' <button  class="btn btn-icon btn-sm btn-danger-light product-btn" onclick="eliminar_hito(' . $value['idhitos'] . ', \'' . encodeCadenaHtml($value['titulo_hito']) . '\')" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>',
              "2" => '<b>'.$value['titulo_hito']. '</b>',
              "3" => $value['descripcion'],
              "4" => $value['fecha_entrega'],
              "5" => ($value['estado_activo'] == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Desactivado</span>'
            ];

          }
          $results =[
            'status'=> true,
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
          ];
          echo json_encode($results);

        } else { echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data']; }
      break;

      case 'editar_perfil':
        $rspta = $Perfil_proyecto->editar_perfil($_GET["idusuario"], $titulo_p, $descripcion_p, $fecha_i, $fecha_e, $link_p);
        echo json_encode($rspta);
      break;

      case 'agregar_editar_hito':
        if (empty($idhitos)) {
          $rspta = $Perfil_proyecto->insertar_hito($_GET["idequipo"], $nombre_hito, $fecha_hito_e, $descr_hito);
          echo json_encode($rspta, true);
        } else {
          $rspta = $Perfil_proyecto->editar_hito($idhitos, $idperfil_pi, $nombre_hito, $fecha_hito_e, $descr_hito);
          echo json_encode($rspta, true);
        }
      break;

      case 'mostrar_hito':
        $rspta = $Perfil_proyecto->mostrar_hito($idhitos);
        echo json_encode($rspta);
      break;

      case 'desactivar_hito':
        $rspta = $Perfil_proyecto->desactivar_hito($_GET["id_tabla"]);
        echo json_encode($rspta, true);
      break;

      case 'eliminar_hito':
        $rspta = $Perfil_proyecto->eliminar_hito($_GET["id_tabla"]);
        echo json_encode($rspta, true);
      break;
    }



  } else {
    $retorno = ['status'=>'nopermiso', 'message'=>'No tienes acceso a este modulo, pide acceso a tu administrador', 'data' => [], 'aaData' => [] ];
    echo json_encode($retorno);
  }  
}

ob_end_flush();
?>