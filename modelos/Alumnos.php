<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Alumnos{

  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }


  public function listar_estudiantes($idperiodo){
    $sql_1 = "SELECT e.idestudiante, s.idsemestre, p.nombres, p.apellidos, p.numero_documento, p.foto_perfil, c.nombre_carrera, s.ciclo, s.grupo
              FROM semestre AS s
              INNER JOIN estudiante AS e ON s.idsemestre = e.idsemestre
              INNER JOIN persona AS p ON e.idpersona = p.idpersona
              INNER JOIN carrera AS c ON s.idcarrera = c.idcarrera
              WHERE s.idsemestre = '$idperiodo' 
              AND s.estado = '1' AND s.estado_delete = '1'
              ORDER BY p.apellidos ASC;";
    $estudiante = ejecutarConsultaArray($sql_1);

    $sql_2 = "SELECT cs.idcursos_x_semestre, c.idcurso, c.asignatura
              FROM cursos_x_semestre AS cs
              INNER JOIN semestre AS s ON s.idsemestre = cs.idsemestre
              INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
              WHERE s.idsemestre = '$idperiodo';";
    $cursos_afi = ejecutarConsultaArray($sql_2);

    $data = [
      'status' => true,
      'message' => 'todo okey',
      'data' => [
        'estudiante' => $estudiante['data'],
        'cursos' => $cursos_afi['data']
      ]
      ];

    return $data;
  }

  public function listar_alumnos($idsemestre, $idcurso){
    $sql = "SELECT e.idestudiante, p.nombres, p.apellidos, c.asignatura, p.numero_documento, p.foto_perfil
            FROM cursos_x_semestre AS cs
            INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
            INNER JOIN semestre AS s ON s.idsemestre = cs.idsemestre
            INNER JOIN estudiante AS e ON s.idsemestre = e.idsemestre
            INNER JOIN persona AS p ON e.idpersona = p.idpersona
            WHERE c.idcurso = '$idcurso' 
              AND s.idsemestre = '$idsemestre' 
              AND (e.idequipo IS NULL OR e.idequipo = '')
            ORDER by p.apellidos ASC";
    return ejecutarConsultaArray($sql);
  }
}