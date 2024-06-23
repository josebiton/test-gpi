<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['alumnos'] == 1) {

    require_once "../modelos/Alumnos.php";
    $alumnos = new Alumnos();

    date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

    // $idcromograma_tareas      = isset($_POST["idcromograma_tareas"])? limpiarCadena($_POST["idcromograma_tareas"]):"";

    switch ($_GET["op"]) {

      case 'tabla_principal_alumnos':
        $rspta = $alumnos->listar_estudiantes($_GET["idperiodo"]);

        $data = []; $count = 1; $cursos_afi = '';

        if($rspta['status'] == true){

          foreach($rspta['data']['cursos'] as $key => $value){
            $cursos_afi .= $value['asignatura'].'<br/>';
          }
          
          foreach($rspta['data']['estudiante'] as $key => $value){
            $data[]=[
              "0" => $count++,
              "1" =>  '<div class="d-flex flex-fill align-items-center">
                        <div class="me-2 cursor-pointer" data-bs-toggle="tooltip" title="User"><span class="avatar"> <img src="../assets/modulo/usuario/perfil/no-perfil.jpg" alt="..."> </span></div>
                        <div>
                          <h6 class="d-block fw-semibold text-primary"> '. $value['apellidos'] .' '. $value['nombres'] .'</h6>
                          <span class="text-muted"><b>Cod.</b>: '. $value['numero_documento'] .'</span>
                        </div>
                      </div>',

              "2" =>  '<span class="badge bg-secondary-transparent fs-6">'.$value['nombre_carrera'].'</span> <br/>
                       <span class="badge bg-secondary-transparent">Ciclo: '. $value['ciclo'].'</span> ->
                       <span class="badge bg-secondary-transparent">Grupo: '. $value['grupo'].'</span>',
              "3" => $cursos_afi,
              "4" => '<button class="btn btn-icon btn-success-light" onclick="mostrar_rol_estudiante(' . $value['idestudiante'] . ')" data-bs-toggle="tooltip" title="Ver Equipo"><i class="ri-logout-box-r-line"></i></button>'
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

    }

  } else {
    $retorno = ['status'=>'nopermiso', 'message'=>'No tienes acceso a este modulo, pide acceso a tu administrador', 'data' => [], 'aaData' => [] ];
    echo json_encode($retorno);
  }  
}

ob_end_flush();
?>