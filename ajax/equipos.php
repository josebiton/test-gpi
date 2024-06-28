<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['equipos'] == 1) {

    require_once "../modelos/Alumnos.php";
    require_once "../modelos/Equipos.php";
    $equipos = new Equipos();
    $alumnos = new Alumnos();

    date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

    $n_equipo      = isset($_POST["n_equipo"])? limpiarCadena($_POST["n_equipo"]):"";
    $codigo_equipo      = isset($_POST["codigo_equipo"])? limpiarCadena($_POST["codigo_equipo"]):"";
    $estudiantes_seleccionados = isset($_POST["estudiantes_seleccionados"]) ? limpiarCadena($_POST["estudiantes_seleccionados"]) : "";



    switch ($_GET["op"]) {

      case 'lista_tabla_alumnos':
        $rspta = $alumnos->listar_alumnos($_GET["idsemestre"], $_GET["idcurso"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          
          foreach($rspta['data'] as $key => $value){
            $data[]=[
              "0" => $count++,
              "1" => '<div class="d-flex flex-fill align-items-center student-row" data-idestudiante="' . $value['idestudiante'] . '">
                        <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="User"><span class="avatar"> <img src="../assets/modulo/usuario/perfil/no-perfil.jpg" alt="..."> </span></div>
                        <div>
                          <h6 class="d-block fw-semibold text-primary"> '. $value['apellidos'] .' '. $value['nombres'] .'</h6>
                          <span class="text-muted"><b>Cod.</b>: '. $value['numero_documento'] .'</span>
                        </div>
                      </div>',
              "2" =>  '<button class="btn btn-icon btn-sm btn-success-light" onclick="mover_estudiante(' . $value['idestudiante'] . ')" data-bs-toggle="tooltip" title="Seleccionar"><i class="ri-arrow-right-line"></i></button>',
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

      case 'ultimo_equipo':
        $rspta = $equipos->ultimo_equipo($_POST["idsemestre"], $_POST["idcurso"]);
        echo json_encode($rspta);
      break;

      case 'guardar_equipo':
        $rspta = $equipos->crear_y_actualizar_equipo($n_equipo, $codigo_equipo, $estudiantes_seleccionados);
        echo json_encode($rspta);
      break;

      case 'listar_equipos':
        $rspta = $equipos->listar_equipos($_POST["idsemestre"], $_POST["idcurso"]);
        echo json_encode($rspta);
      break;

      case 'datos_equipo':
        $rspta = $equipos->datos_equipo($_POST["idequipo"]);
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