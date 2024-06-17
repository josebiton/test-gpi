<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Usuario_e{
  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }


  public function info_estudiante($idusuario, $idsemestre){
    $sql_1 = "SELECT p.nombres, p.apellidos, p.numero_documento,
              f.nombre_facultad, c.nombre_carrera, s.ciclo,
              p.correo, p.celular, p.direccion
            FROM estudiante AS es
            INNER JOIN persona AS p ON es.idpersona = p.idpersona
            INNER JOIN usuario AS u ON p.idpersona = u.idpersona
            INNER JOIN semestre AS s ON es.idsemestre = s.idsemestre
            INNER JOIN carrera AS c ON s.idcarrera = c.idcarrera
            INNER JOIN facultad AS f ON c.idfacultad = f.idfacultad
            WHERE s.idsemestre = '$idsemestre' AND u.idusuario = '$idusuario';";
    $datos_est = ejecutarConsultaSimpleFila($sql_1);

    $sql_2 = "SELECT LPAD(COUNT(e.idequipo), 2, '0') as total_equipo
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
              WHERE u.idusuario = '$idusuario';";
    $n_proyectos = ejecutarConsultaSimpleFila($sql_2);

    $sql_3 = "SELECT COUNT(e.idequipo) as total_aprobado
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
              INNER JOIN equipos_pi AS q ON e.idequipo = q.idequipos_pi
              WHERE u.idusuario = '$idusuario' AND q.calificacion_final > 13;";
    $aprobados = ejecutarConsultaSimpleFila($sql_3);

    $sql_4 = "SELECT s.ciclo, s.periodo, pp.titulo_proyecto, q.nombre_equipo, q.titulo_equipo, q.calificacion_final
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
              INNER JOIN equipos_pi AS q ON e.idequipo = q.idequipos_pi
              INNER JOIN semestre AS s ON e.idsemestre = s.idsemestre
              LEFT JOIN perfil_del_pi AS pp ON q.idequipos_pi = pp.idnum_equipo
              WHERE u.idusuario = '$idusuario';";
    $proyectos = ejecutarConsultaArray($sql_4);

    $data = [
      'status' => true,
      'message' => 'todo okey',
      'data' => [
        'estudiante' => $datos_est['data'],
        'num_pyt' => $n_proyectos['data'],
        'aprob' => $aprobados['data'],
        'proyectos' => $proyectos['data']
      ]
    ];
    return $data;
  }


}