<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['cronograma'] == 1) {

    require_once "../modelos/Cronograma.php";
    $cronograma = new Cronograma();

    date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

    $idcromograma_tareas      = isset($_POST["idcromograma_tareas"])? limpiarCadena($_POST["idcromograma_tareas"]):"";
    $idperfil      = isset($_POST["idperfil"])? limpiarCadena($_POST["idperfil"]):"";
    $nombre_a     = isset($_POST["nombre_a"])? limpiarCadena($_POST["nombre_a"]):"";
    $duracion_a   = isset($_POST["duracion_a"])? limpiarCadena($_POST["duracion_a"]):"";
    $descr_a      = isset($_POST["descr_a"])? limpiarCadena($_POST["descr_a"]):"";
    $fecha_i_a    = isset($_POST["fecha_i_a"])? limpiarCadena($_POST["fecha_i_a"]):"";
    $fecha_e_a    = isset($_POST["fecha_e_a"])? limpiarCadena($_POST["fecha_e_a"]):"";
    $es_entreg_a  = isset($_POST["es_entreg_a"])? limpiarCadena($_POST["es_entreg_a"]):"";

    $idcrono = isset($_POST["idcrono"])? limpiarCadena($_POST["idcrono"]):"";

    switch ($_GET["op"]) {

      case 'tabla_principal_crono':
        $rspta = $cronograma->tabla_crono($_GET["idequipo"]);

        $data = []; $count = 1;

        if($rspta['status'] == true){
          foreach($rspta['data'] as $key => $value){

            $fecha_actual = strtotime(date('Y-m-d')); // Fecha actual en formato timestamp
            $fecha_cierre = strtotime($value['fecha_cierre']); // Fecha de cierre en formato timestamp

            // Comparar las fechas
            if ($fecha_cierre < $fecha_actual) {
              $est_vigencia = 'Finalizado';
            } else {
              $est_vigencia = 'Vigente';
            }

            $data[]=[
              "0" => $count++,
              "1" =>  '<button class="btn btn-icon btn-sm btn-warning-light" onclick="mostrar_crono(' . $value['idcromograma_tareas'] . ')" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-line"></i></button>'.
                      ' <button  class="btn btn-icon btn-sm btn-danger-light product-btn" onclick="eliminar_crono(' . $value['idcromograma_tareas'] . ', \'' . encodeCadenaHtml($value['nombre_actividad']) . '\')" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>',
              "2" =>  '<div class="d-flex flex-fill align-items-center">
                        <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="Activity"><span class="avatar"> <img src="../assets/modulo/cronograma/default_crono.png" alt="..."> </span></div>
                        <div>
                          <span class="d-block fw-semibold text-primary">'. $value['nombre_actividad'] .'</span>
                          <span class="text-muted"><b>Estado</b>: '.$est_vigencia.'</span>
                        </div>
                      </div>',
              "3" => '<textarea cols="30" rows="2" class="textarea_datatable bg-light" readonly="">'.$value['descripcion_actividad'].'</textarea>',
              "4" => '<span class="fw-semibold text-secondary">'.$value['dia_duracion'].' días</span>',
              "5" => $value['fecha_inicio'],
              "6" => $value['fecha_cierre'],
              "7" => ($value['es_entregable'] == '1') ? '<div class="d-flex justify-content-center"><button class="btn btn-icon btn-sm btn-info-light" onclick="show_hide_entregable(2); enviar_idcrono(' . $value['idcromograma_tareas'] . '); mostrar_archivos(' . $value['idcromograma_tareas'] . ')" data-bs-toggle="tooltip" title="Ver"><i class="ti ti-file-dollar fs-lg"></i></button></div>' : 
                '<span class="badge bg-danger-transparent">No entregable</span>',

              "8"=> $value['nombre_actividad'],
              "9"=> $value['descripcion_actividad'],
              "10"=> $value['dia_duracion']
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

      case 'guardar_editar_crono':
        if (empty($idcromograma_tareas)) {
          $rspta = $cronograma->insertar_crono($_GET["idequipo"], $nombre_a, $duracion_a, $descr_a, $fecha_i_a, $fecha_e_a, $es_entreg_a);
          echo json_encode($rspta, true);
        } else {
          $rspta = $cronograma->editar_crono($idcromograma_tareas, $idperfil, $nombre_a, $duracion_a, $descr_a, $fecha_i_a, $fecha_e_a, $es_entreg_a);
          echo json_encode($rspta, true);
        }
      break;

      case 'mostrar_crono':
        $rspta = $cronograma->mostrar_crono($idcromograma_tareas);
        echo json_encode($rspta);
      break;

      case 'desactivar_crono':
        $rspta = $cronograma->desactivar_crono($_GET["id_tabla"]);
        echo json_encode($rspta, true);
      break;

      case 'eliminar_crono':
        $rspta = $cronograma->eliminar_crono($_GET["id_tabla"]);
        echo json_encode($rspta, true);
      break;

      case 'traer_fecha_crono':
        $rspta = $cronograma->traer_fecha_crono($_POST["idequipo"]);
        echo json_encode($rspta);
      break;


      case 'guardar_doc':
                 
        $ext = explode(".", $_FILES["doc"]["name"]);
        $flat_img = true;
        $nom_entrg = $date_now . '__' . random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext);
        move_uploaded_file($_FILES["doc"]["tmp_name"], "../assets/modulo/cronograma/" . $nom_entrg);    

        $rspta = $cronograma->guardar_doc($idcrono, $nom_entrg);
        echo json_encode($rspta);  
        
      break;

      case 'mostrar_archivos':
        $idcrono = $_POST["idcrono"];
        $rspta = $cronograma->mostrar_archivos($idcrono);
        echo json_encode($rspta);
      break;

      case 'mostrar_archivo':
        $identregables = $_POST["identregables"];
        $rspta = $cronograma->mostrar_archivo($identregables);
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