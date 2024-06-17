var idusuario;
var idcarrera;
var idsemestre;
var idequipo;

function init(){

  traer_data_filtro();
  info_estudiante();

}

// :::::::::::::::::  I N F O R M A C I O N   D E L    U S U A R I O  :::::::::::::::::::::::::

function info_estudiante(){
  $.post("../ajax/usuario_e.php?op=info_estudiante", { idusuario: idusuario, idsemestre: idsemestre }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      
      $("#nombre_e").text(e.data.estudiante.nombres+" "+e.data.estudiante.apellidos);
      $("#doc_identidad").text("Código: "+e.data.estudiante.numero_documento);
      $("#n_pyt").text(e.data.num_pyt.total_equipo);
      $("#n_grp").text(e.data.num_pyt.total_equipo);
      $("#n_apb").text(e.data.aprob.total_aprobado);

      var html_info = `
        <p class="me-1">
            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                <i class="ri-building-line align-middle fs-14"></i>
            </span>
            ${e.data.estudiante.nombre_facultad ?? ""}
        </p>
        <p class="me-1">
            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                <i class="ri-computer-line align-middle fs-14"></i>
            </span>
            ${e.data.estudiante.nombre_carrera ?? ""}
        </p>
        <p class="me-0">
            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                <i class="ri-calendar-line align-middle fs-14"></i>
            </span>
            Ciclo ${e.data.estudiante.ciclo ?? ""}
      </p>`;

      var html_contac = `
        <p class="me-1">
          <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
            <i class="ri-mail-line align-middle fs-14"></i>
          </span>
          ${e.data.estudiante.correo ?? ""}
          
        </p>
        <p class="me-1">
          <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
            <i class="ri-phone-line align-middle fs-14"></i>
          </span>
          ${e.data.estudiante.celular ?? ""}
          
        </p>
        <p class="me-0">
          <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
            <i class="ri-map-pin-line align-middle fs-14"></i>
          </span>
          ${e.data.estudiante.direccion ?? ""}
          
      </p>`;

      var html_cronologia = '<ul class="list-unstyled profile-timeline">';

        // Recorrer la lista de proyectos
        e.data.proyectos.forEach(function(proyecto) {
          html_cronologia += `
            <li>
              <div>
                <span class="avatar avatar-sm bg-primary-transparent avatar-rounded profile-timeline-avatar">${proyecto.ciclo}</span>
                <p class="mb-2">${proyecto.titulo_proyecto}</p>
                <p class="text-muted mb-0"><b>Equipo: </b>${proyecto.titulo_equipo} -> <b>Ponderado: </b>${proyecto.calificacion_final}</p>
                <p class="text-muted mb-0"><b>Periodo: </b>${proyecto.periodo} => <b>N° Equipo: </b>${proyecto.nombre_equipo}</p>
              </div>
            </li>`;
        });

      html_cronologia += '</ul>';


      $("#info_est").html(html_info);
      $("#info_contac").html(html_contac);
      $("#crono_pi").html(html_cronologia);

    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
  
}






// ::::::::::::::::::::::::: F O R M   V A L I D A T I O N :::::::::::::::::::::::::




// :::::::::::::::::::::::::: F U N C I O N E S   A D I C I O N A L E S  :::::::::::::::::::::::::::
function traer_data_filtro(){
  var storedData = localStorage.getItem('nube_id_usuario');
  if (storedData) {
    var parsedData = JSON.parse(storedData);
    
    idusuario = parsedData.idusuario;
    idcarrera = parsedData.idcarrera;
    idsemestre = parsedData.idsemestre;
    idequipo = parsedData.idequipo;

  // Ahora puedes usar estas variables como necesites
  console.log(idusuario, idcarrera, idsemestre, idequipo);
  } else {
    // Manejar el caso donde no hay datos en localStorage
    console.log('No se encontraron datos en localStorage');
  }
}

$(document).ready(function () {
  init();
});