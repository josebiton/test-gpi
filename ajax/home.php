<?php
ob_start();
if (strlen(session_id()) < 1) { session_start(); }//Validamos si existe o no la sesión

if (!isset($_SESSION["user_nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado, inicia nuevamente', 'data' => [], 'aaData' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['escritorioE'] == 1) {

    require_once "../modelos/Home.php";
    $home = new Home();

    switch ($_GET["op"]) {

      case 'filtro_ua':
        $rspta = $home->listar_carrera($_POST['idusuario']);
        echo json_encode($rspta);
      break;

      case 'filtro_ub':
        $rspta = $home->listar_periodo($_POST['idcarrera']);
        echo json_encode($rspta);
      break;

      case 'filtro_uc':
        $rspta = $home->listar_equipo($_POST['idsemestre']);
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