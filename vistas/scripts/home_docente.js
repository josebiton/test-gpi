var idusuario;
var idperiodo;
var idcurso;
var idgrupo;

function init(){
  filtro_ca();
  console.log(idusuario, idperiodo, idcurso, idgrupo);

}

// ::::::::::::::::::::E S C R I T O R I O   D O C E N T E ::::::::::::::::::::::::::





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
  var selectedCurso = document.getElementById('filtro_b2').value;
  var selectedGrupo = document.getElementById('filtro_c3').value;

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