<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  Class Home
  {
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

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

    public function listar_periodo($idcarrera){
      $sql = "SELECT s.idsemestre, s.periodo
      FROM semestre AS s
      INNER JOIN carrera AS c ON s.idcarrera = c.idcarrera
      WHERE c.idcarrera = '$idcarrera' AND c.estado = '1' AND c.estado_delete = '1'
      ORDER BY s.periodo DESC;";
      return ejecutarConsultaArray($sql);
    }

    public function listar_equipo($idsemestre){
      $sql = "SELECT epi.idequipos_pi as idequipo, epi.nombre_equipo
      FROM semestre AS s
      INNER JOIN estudiante AS e ON s.idsemestre = e.idsemestre
      INNER JOIN equipos_pi AS epi ON epi.idequipos_pi = e.idequipo
      WHERE s.idsemestre = '$idsemestre' AND epi.estado = '1' AND epi.estado_delete = '1'
      ORDER BY epi.nombre_equipo DESC;";
      return ejecutarConsultaArray($sql);
    }


  }
?>