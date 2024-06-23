var idusuario;
var idperiodo;
var idcurso;
var idgrupo;

function init(){
  filtro_ca();
  console.log(idusuario, idperiodo, idcurso, idgrupo);

  lista_equipos();

}

// ::::::::::::::::::::E S C R I T O R I O   D O C E N T E ::::::::::::::::::::::::::
function lista_equipos() {
  $.post("../ajax/equipos.php?op=listar_equipos", { idsemestre: idperiodo, idcurso: idcurso }, function (e, status) {
    e = JSON.parse(e);

    if (e.status === true) {
      e.data.forEach((val, key) => {

        $titulo = val.titulo_equipo ? val.titulo_equipo : "";
        $hitos = val.numero_hitos ? val.numero_hitos : 0;

        let valor1 = val.titulo_proyecto ? 4 : 0;
        let valor2 = val.descripcion_proyecto ? 4 : 0;
        let valor3 = val.num_rol ? 4 : 0;
        let valor4 = val.numero_hitos ? 8 : 0;

        let valor5;
        if (val.num_crono) {
            if (val.num_crono > 5) {
              valor5 = 10;
            } else if (val.num_crono > 0) {
              valor5 = 5;
            } else {
              valor5 = 0;
            }
        } else {
          valor5 = 0;
        }

        let porcentaje_avance = valor1 + valor2 + valor3 + valor4 + valor5;

        var html_equipos = `<div class="col-xl-3">
          <div class="card custom-card overlay-card">
            <img src="../assets/images/media/media-34.jpg" class="card-img" alt="...">
            <div class="card-img-overlay d-flex flex-column p-0">
              <div class="card-header">
                <div class="card-title text-fixed-white">
                  <div class="row">
                    <div class="col-xl-9">${$titulo}</div>
                    <div class="col-xl-3"><span class="fs-12 badge bg-light text-dark">${val.nombre_equipo}</span></div>
                  </div>
                </div>
              </div>
              <div class="card-body text-fixed-white">
                <div class="card-text">
                  <div class="row">
                    <div class="col-xxl-6 col-lg-6 col-md-6 col-xl-6 gy-2">
                      <span class="fs-15"><b>N° Integrantes:</b> ${val.num_estudiantes}</span><br>
                      <span class="fs-15"><b>N° Hitos:</b> ${$hitos}</span><br>
                      <span class="fs-15"><b>Ponderado:</b> ${val.calificacion_final}</span><br>
                    </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6 col-xl-6">
                      <div id="crm-main-${key}"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>`;

        $("#div-lista-de-equipos").append(html_equipos);

        // Crear gráfico circular del porcentaje de avance de cada equipo
        var options = {
          chart: {
              height: 127,
              width: 100,
              type: "radialBar",
          },
      
          series: [porcentaje_avance],
          colors: ["rgba(128,128,128,0.9)"],
          plotOptions: {
              radialBar: {
                  hollow: {
                      margin: 0,
                      size: "55%",
                      background: "#fff"
                  },
                  dataLabels: {
                      name: {
                          offsetY: -10,
                          color: "#4b9bfa",
                          fontSize: ".625rem",
                          show: false
                      },
                      value: {
                          offsetY: 5,
                          color: "#4b9bfa",
                          fontSize: ".875rem",
                          show: true,
                          fontWeight: 600
                      }
                  }
              }
          },
          stroke: {
              lineCap: "round"
          },
          labels: ["Status"]
        };

        // Cada equipo tendrá su propio gráfico
        document.querySelector(`#crm-main-${key}`).innerHTML = ``;
        var chart = new ApexCharts(document.querySelector(`#crm-main-${key}`), options);
        chart.render();

      });

    } else {
      ver_errores(e);
    }

  }).fail(function (e) {
    ver_errores(e);
  });
}




// :::::::::::::::::::::::::::::: F I L T R O S ::::::::::::::::::::::::::::::::::::::
function filtro_ca(){

  $.post("../ajax/home.php?op=filtro_ca", {idusuario: idusuario}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      $("#filtro_a1").empty();
      e.data.forEach(function(semestre) {
        $("#filtro_a1").append('<option value="' + semestre.idsemestre + '">' + semestre.periodo + '</option>');
      });
      if (idperiodo) { $("#filtro_a1").val(idperiodo); }
      
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

  filtro_cb();
}

function filtro_cb(){
  var selt_idperiodo = $("#filtro_a1").val();
  var $id = selt_idperiodo && selt_idperiodo !== "" ? selt_idperiodo : idperiodo;
  
  $.post("../ajax/home.php?op=filtro_cb", {idperiodo: $id, idusuario: idusuario}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      //listar los datos de una carrera VALUE = e.data.idcarrera  TEXT = e.data.nombre_carrera
      $("#filtro_b1").empty();
      e.data.forEach(function(curso) {
        $("#filtro_b1").append('<option value="' + curso.idcurso + '">' + curso.asignatura + '</option>');
      });
      if (idcurso) { $("#filtro_b1").val(idcurso); }
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

  filtro_cc();
}

function filtro_cc(){
  var selt_idcurso = $("#filtro_b1").val();
  var $id = selt_idcurso && selt_idcurso !== "" ? selt_idcurso : idcurso;

  $.post("../ajax/home.php?op=filtro_cc", {idcurso: $id, idusuario: idusuario}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      //listar los datos de una carrera VALUE = e.data.idcarrera  TEXT = e.data.nombre_carrera
      $("#filtro_c1").empty();
      e.data.forEach(function(grp) {
        $("#filtro_c1").append('<option value="' + grp.idsemestre + '">' + grp.grupo + '</option>');
      });
      if (idgrupo) { $("#filtro_c1").val(idgrupo); }
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );
}


// Función para actualizar localStorage con los nuevos valores y recargar la página
function filtrar_curso() {
  // Capturar los valores seleccionados en los selects
  var selectedPeriodo = document.getElementById('filtro_a1').value;
  var selectedCurso = document.getElementById('filtro_b1').value;
  var selectedGrupo = document.getElementById('filtro_c1').value;

  // Actualizar las variables globales
  globalVars.idfta = selectedPeriodo;
  globalVars.idftb = selectedCurso;
  globalVars.idftc = selectedGrupo;

  // Mantener idusuario y actualizar localStorage
  localStorage.setItem('nube_id_usuario', JSON.stringify(globalVars));

  console.log('LocalStorage actualizado', globalVars);

  // Recargar la página para aplicar los cambios
  location.reload();
}


function traer_data_filtro(){
  var storedData = localStorage.getItem('nube_id_usuario');
  if (storedData) {
    var parsedData = JSON.parse(storedData);
    
    idusuario = parsedData.idusuario;
    idperiodo = parsedData.idfta;
    idcurso = parsedData.idftb;
    idgrupo = parsedData.idftc;

  // Ahora puedes usar estas variables como necesites
  console.log(idusuario, idperiodo, idcurso, idgrupo);
  } else {
    // Manejar el caso donde no hay datos en localStorage
    console.log('No se encontraron datos en localStorage');
  }
}
traer_data_filtro();


$(document).ready(function () {
  init();
});