<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  Class Home
  {
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    // :::::::::::::::::::::::::::: E S T U D I A N T E ::::::::::::::::::::::::::::::
    public function listar_carrera($idusuario){
      $sql = "SELECT DISTINCT c.idcarrera, c.abreviatura
      FROM usuario AS u
      INNER JOIN persona AS p ON u.idpersona = p.idpersona
      INNER JOIN estudiante AS e ON p.idpersona = e.idpersona
      INNER JOIN semestre AS s ON e.idsemestre = s.idsemestre
      INNER JOIN carrera AS c ON s.idcarrera = c.idcarrera
      WHERE u.idusuario = '$idusuario' AND c.estado ='1' AND c.estado_delete = '1';";
      return ejecutarConsultaArray($sql);
    }

    public function listar_periodo($idcarrera, $idusuario){
      $sql = "SELECT s.idsemestre, s.periodo
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN estudiante AS e ON e.idpersona = p.idpersona
              INNER JOIN semestre AS s ON e.idsemestre = s.idsemestre
              INNER JOIN carrera AS c ON s.idcarrera = c.idcarrera
              WHERE u.idusuario = '$idusuario' 
              AND c.idcarrera = '$idcarrera'
              AND c.estado = '1' AND c.estado_delete = '1'
              ORDER BY s.periodo DESC;";
      return ejecutarConsultaArray($sql);
    }

    public function listar_equipo($idperiodo, $idusuario){
      $sql = "SELECT epi.idequipos_pi as idequipo, epi.nombre_equipo
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN estudiante AS e ON e.idpersona = p.idpersona
              INNER JOIN semestre AS s ON e.idsemestre = s.idsemestre
              INNER JOIN equipos_pi AS epi ON epi.idequipos_pi = e.idequipo
              WHERE u.idusuario = '$idusuario' AND s.idsemestre = '$idperiodo'
              ORDER BY epi.nombre_equipo DESC;";
      return ejecutarConsultaArray($sql);
    }

    
    // :::::::::::::::::::::::::::: D O C E N T E ::::::::::::::::::::::::::::::
    public function listar_periodoD($idusuario){
      $sql = "SELECT DISTINCT s.idsemestre, s.periodo
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN cursos_x_semestre AS cs ON p.idpersona = cs.iddocente
              INNER JOIN semestre AS s ON cs.idsemestre = s.idsemestre
              WHERE u.idusuario = '$idusuario' AND cs.estado = '1' AND cs.estado_delete = '1';";
      return ejecutarConsultaArray($sql);
    }

    public function listar_cursosD($idperiodo, $idusuario){
      $sql = "SELECT c.idcurso, c.asignatura
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN cursos_x_semestre AS cs ON p.idpersona = cs.iddocente
              INNER JOIN semestre AS s ON cs.idsemestre = s.idsemestre
              INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
              WHERE s.idsemestre = '$idperiodo' AND u.idusuario = '$idusuario'
              AND s.estado = '1' AND s.estado_delete = '1';";
      return ejecutarConsultaArray($sql);
    }

    public function listar_grupo($idcurso, $idusuario){
      $sql = "SELECT s.idsemestre, s.grupo
              FROM usuario AS u
              INNER JOIN persona AS p ON u.idpersona = p.idpersona
              INNER JOIN cursos_x_semestre AS cs ON p.idpersona = cs.iddocente
              INNER JOIN semestre AS s ON cs.idsemestre = s.idsemestre
              INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
              WHERE c.idcurso = '$idcurso' AND u.idusuario = '$idusuario'
              AND c.estado = '1' AND c.estado_delete = '1'";
    return ejecutarConsultaArray($sql);
    }


  }
?>