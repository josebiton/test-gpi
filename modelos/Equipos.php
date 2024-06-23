<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Equipos{

  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
  }


  public function listar_equipos($idsemestre, $idcurso){
    $sql = "SELECT eq.idequipos_pi, eq.nombre_equipo, eq.titulo_equipo, eq.unique_code, c.idcurso, s.idsemestre, eq.calificacion_final, 
                   pi.idperfil_del_pi, pi.titulo_proyecto, pi.descripcion_proyecto, h.num_hitos, cg.num_crono, est.num_estudiantes, 
                   rol.num_rol, hit.numero_hitos
            FROM semestre AS s
            INNER JOIN cursos_x_semestre AS cs ON s.idsemestre = cs.idsemestre
            INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
            INNER JOIN estudiante AS e ON s.idsemestre = e.idsemestre
            INNER JOIN equipos_pi AS eq ON e.idequipo = eq.idequipos_pi
            LEFT JOIN perfil_del_pi AS pi ON eq.idequipos_pi = pi.idnum_equipo
            LEFT JOIN (
                SELECT idperfil_pi, COUNT(idhitos) AS num_hitos
                FROM hitos
                GROUP BY idperfil_pi
            ) AS h ON pi.idperfil_del_pi = h.idperfil_pi
            LEFT JOIN (
                SELECT idperfil_pi, COUNT(idcromograma_tareas) AS num_crono
                FROM cromograma_actividad
                GROUP BY idperfil_pi
            ) AS cg ON pi.idperfil_del_pi = cg.idperfil_pi
            LEFT JOIN (
                SELECT e.idequipo, COUNT(e.idestudiante) AS num_estudiantes
                FROM estudiante AS e
                GROUP BY e.idequipo
            ) AS est ON eq.idequipos_pi = est.idequipo
            LEFT JOIN (
                SELECT e.idequipo, COUNT(e.rol_proyecto) AS num_rol
                FROM estudiante AS e
                WHERE e.rol_proyecto IS NOT NULL
                GROUP BY e.idequipo
            ) AS rol ON eq.idequipos_pi = rol.idequipo
            LEFT JOIN (
                SELECT pi.idnum_equipo, COUNT(h.idhitos) AS numero_hitos
                FROM perfil_del_pi AS pi
                INNER JOIN hitos AS h ON pi.idperfil_del_pi = h.idperfil_pi
                GROUP BY pi.idnum_equipo
            ) AS hit ON eq.idequipos_pi = hit.idnum_equipo
            WHERE s.idsemestre = '$idsemestre' AND c.idcurso = '$idcurso'
            GROUP BY 
                eq.idequipos_pi, eq.nombre_equipo, eq.titulo_equipo, eq.unique_code, c.idcurso, s.idsemestre, 
                eq.calificacion_final, pi.idperfil_del_pi, pi.titulo_proyecto, pi.descripcion_proyecto, 
                h.num_hitos, cg.num_crono, est.num_estudiantes, rol.num_rol, hit.numero_hitos;";

    return ejecutarConsultaArray($sql);
  }

  public function ultimo_equipo($idsemestre, $idcurso){
    $sql = "SELECT eq.nombre_equipo
            FROM semestre AS s
            INNER JOIN cursos_x_semestre AS cs ON s.idsemestre = cs.idsemestre
            INNER JOIN cursos AS c ON cs.idcurso = c.idcurso
            INNER JOIN estudiante AS e ON s.idsemestre = e.idsemestre
            INNER JOIN persona AS p ON e.idpersona = p.idpersona
            INNER JOIN equipos_pi AS eq ON e.idequipo = eq.idequipos_pi
            WHERE s.idsemestre = '$idsemestre' AND c.idcurso = '$idcurso'
              AND eq.estado = '1' AND eq.estado_delete = '1'
            ORDER BY eq.nombre_equipo DESC LIMIT 1;";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function crear_y_actualizar_equipo($n_equipo, $codigo_equipo, $estudiantes_seleccionados) {
    // PRIMERO: Crear el equipo
    $sql_0 = "INSERT INTO equipos_pi(nombre_equipo, unique_code) VALUES('$n_equipo', '$codigo_equipo')";
    $insertar_equipo = ejecutarConsulta_retornarID($sql_0);

    // SEGUNDO: Buscar el ID del equipo creado
    $sql_1 = "SELECT idequipos_pi FROM equipos_pi WHERE unique_code = '$codigo_equipo'";
    $buscar_equipo = ejecutarConsultaSimpleFila($sql_1);

    $idequipo = '';
    if ($buscar_equipo['status']) {
        if (!empty($buscar_equipo['data']['idequipos_pi'])) {
            $idequipo = $buscar_equipo['data']['idequipos_pi'];
        } else {
            return array('status' => false, 'message' => $buscar_equipo['message']);
        }
    } else {
        return array('status' => false, 'message' => 'Error en la consulta: ' . $buscar_equipo['message']);
    }

    // Convertir la cadena de estudiantes seleccionados en un array
    $idestudiantes = explode(',', $estudiantes_seleccionados);

    // TERCERO: Actualizar cada estudiante con el ID del equipo
    foreach ($idestudiantes as $idestudiante) {
        $sql_2 = "UPDATE estudiante SET idequipo = '$idequipo' WHERE idestudiante = '$idestudiante'";
        $actualizar_estudiante = ejecutarConsulta_retornarID($sql_2);

        if (!$actualizar_estudiante['status']) {
            return array('status' => false, 'message' => 'Error al actualizar el estudiante: ' . $actualizar_estudiante['message']);
        }
    }

    // CUARTO: Crear ID perfil del PI
    $sql_3 = "INSERT INTO perfil_del_pi(idnum_equipo)VALUES('$idequipo')";
    $insertar_perfil = ejecutarConsulta_retornarID($sql_3);

    return array('status' => true, 'message' => 'Equipo y estudiantes actualizados correctamente');
  }
}