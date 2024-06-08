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

    switch ($_GET["op"]) {

      case 'mostrar_perfil_p':
        $rspta = $Perfil_proyecto->mostrar_perfil_p($_POST["idequipo"]);
        echo json_encode($rspta);
      break;

      case 'listar_tabla_hitos':

        $rspta = $Perfil_proyecto->listar_tabla_hitos($_GET["idusuario"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          foreach($rspta['data'] as $key => $value){

            $data[]=[
              "0" => $count++,
              "1" => '',
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
    }



  } else {
    $retorno = ['status'=>'nopermiso', 'message'=>'No tienes acceso a este modulo, pide acceso a tu administrador', 'data' => [], 'aaData' => [] ];
    echo json_encode($retorno);
  }  
}

ob_end_flush();
?>