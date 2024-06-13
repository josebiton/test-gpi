<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['equipo'] == 1) {

    require_once "../modelos/Equipo.php";
    $equipo = new Equipo();

    date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';


    $idequipo      = isset($_POST["idequipo"])? limpiarCadena($_POST["idequipo"]):"";
    $titulo_p      = isset($_POST["titulo_p"])? limpiarCadena($_POST["titulo_p"]):"";
    
    $idestudiante      = isset($_POST["idestudiante"])? limpiarCadena($_POST["idestudiante"]):"";
    $rol_e      = isset($_POST["rol_e"])? limpiarCadena($_POST["rol_e"]):"";


    switch ($_GET["op"]) {

      case 'datos_equipo':
        $rspta = $equipo->datos_equipo($_POST["idequipo"]);
        echo json_encode($rspta);
      break;

      case 'tabla_principal_equipo':
        $rspta = $equipo->listar_equipo($_GET["idequipo"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          foreach($rspta['data'] as $key => $value){

            $data[]=[
              "0" => $count++,
              "1" =>  '<button class="btn btn-icon btn-sm btn-warning-light" onclick="mostrar_rol_estudiante(' . $value['idestudiante'] . ')" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-line"></i></button>',
              "2" =>  '<div class="d-flex flex-fill align-items-center">
                        <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="Activity"><span class="avatar"> <img src="../assets/modulo/usuario/perfil/no-perfil.jpg" alt="..."> </span></div>
                        <div>
                          <h6 class="d-block fw-semibold text-primary">'. $value['nombres'] .' '. $value['apellidos'] .'</h6>
                          <span class="text-muted"><b>Cod.</b>: '. $value['numero_documento'] .'</span>
                        </div>
                      </div>',
              "3" => '<span class="fw-semibold text-secondary">'.$value['rol_proyecto'].'</span>',

              "4" => $value['nombres'] .' '. $value['apellidos'],
              "5" => $value['rol_proyecto']
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

      case 'editar_rol':
        $rspta = $equipo->editar_rol($idestudiante, $rol_e);
        echo json_encode($rspta);
      break;

      case 'editar_titulo':
        $rspta = $equipo->editar_titulo($idequipo, $titulo_p);
        echo json_encode($rspta);

      break;

      case 'mostrar_rol_estudiante':
        $rspta = $equipo->mostrar_rol_estudiante($_POST["idestudiante"], $_POST["idequipo"]);
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