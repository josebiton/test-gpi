var idusuario;
var idcarrera;
var idsemestre;
var idequipo;

function init(){
  
  mostrar_perfil();
  console.log(idusuario, idcarrera, idsemestre, idequipo);
  filtro_ua();

}


function mostrar_perfil(){

  $.post("../ajax/perfil_proyecto.php?op=mostrar_perfil_p", {idequipo: idequipo}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      $('#titulo').val(e.data.titulo_proyecto);
      
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

}



// :::::::::::::::::::::::::::::: F I L T R O S ::::::::::::::::::::::::::::::::::::::
function filtro_ua(){

  $.post("../ajax/home.php?op=filtro_ua", {idusuario: idusuario}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      $("#filtro_a").empty();
      e.data.forEach(function(carrera) {
        $("#filtro_a").append('<option value="' + carrera.idcarrera + '">' + carrera.abreviatura + '</option>');
      });
      if (idcarrera) { $("#filtro_a").val(idcarrera); }
      
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

  filtro_ub();
}

function filtro_ub(){

  var selt_idcarrera = $("#filtro_a").val();
  var $car = selt_idcarrera && selt_idcarrera !== "" ? selt_idcarrera : idcarrera;
  
  $.post("../ajax/home.php?op=filtro_ub", {idcarrera: $car}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      //listar los datos de una carrera VALUE = e.data.idcarrera  TEXT = e.data.nombre_carrera
      $("#filtro_b").empty();
      e.data.forEach(function(f2) {
        $("#filtro_b").append('<option value="' + f2.idsemestre + '">' + f2.periodo + '</option>');
      });
      if (idsemestre) { $("#filtro_b").val(idsemestre); }
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

  filtro_uc();
}

function filtro_uc(){

  var selt_idsemestre = $("#filtro_b").val();
  var $id = selt_idsemestre && selt_idsemestre !== "" ? selt_idsemestre : idsemestre;

  $.post("../ajax/home.php?op=filtro_uc", {idsemestre: $id}, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      //listar los datos de una carrera VALUE = e.data.idcarrera  TEXT = e.data.nombre_carrera
      $("#filtro_c").empty();
      e.data.forEach(function(f2) {
        $("#filtro_c").append('<option value="' + f2.idequipo + '">' + f2.nombre_equipo + '</option>');
      });
      if (idequipo) { $("#filtro_c").val(idequipo); }
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );

}



function traer_data_filtro(){
  var storedData = localStorage.getItem('nube_id_usuario');
  if (storedData) {
    var parsedData = JSON.parse(storedData);
    
    idusuario = parsedData.idusuario;
    idcarrera = parsedData.idfta;
    idsemestre = parsedData.idftb;
    idequipo = parsedData.idftc;

  // Ahora puedes usar estas variables como necesites
  console.log(idusuario, idcarrera, idsemestre, idequipo);
  } else {
    // Manejar el caso donde no hay datos en localStorage
    console.log('No se encontraron datos en localStorage');
  }
}

traer_data_filtro();








$(document).ready(function () {
  init();
});