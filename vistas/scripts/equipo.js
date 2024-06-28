var idusuario;
var idcarrera;
var idsemestre;
var idequipo;
var tabla_equipo;

function init(){

  traer_data_filtro();
  listar_equipo();
  $("#guardar_registro_rol").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-rol").submit(); }  });
  $("#guardar_registro_titulo").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-titulo").submit(); }  });

}


function limpiar_form_rol(){
  $("#rol_e").val('');
}

function limpiar_form_t(){
  $("#titulo_p").val('');
}
// ::::::::::::::::::::::::: E Q U I P O  :::::::::::::::::::::::::

function listar_equipo(){

  $.post("../ajax/equipo.php?op=datos_equipo", { idequipo: idequipo }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      var contenido = e.data.titulo_equipo + ' <button class="btn btn-sm btn-success-transparent btn-wave" onclick="mostrar_titulo();"><i class="ri-edit-line"></i></button>';
      $("#titulo_eq").html(contenido);
      $("#num_eq").text(e.data.nombre_equipo);
      $("#titulo_p").text(e.data.titulo_equipo);
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );

  tabla_equipo = $('#tabla-equipo').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-4'B><'col-md-2 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-2 btn btn-sm btn-outline-info btn-wave ", action: function ( e, dt, node, config ) { if (tabla_equipo) { tabla_equipo.ajax.reload(null, false); } } },
      { extend: 'copy', exportOptions: { columns: [0,4,5], }, text: `<i class="fas fa-copy" ></i>`, className: "px-2 btn btn-sm btn-outline-dark btn-wave ", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [0,4,5], }, title: 'Equipo', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-2 btn btn-sm btn-outline-success btn-wave ", footer: true,  }, 
      { extend: 'pdf', exportOptions: { columns: [0,4,5], }, title: 'Equipo', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-2 btn btn-sm btn-outline-danger btn-wave ", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-2 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: '../ajax/equipo.php?op=tabla_principal_equipo&idequipo='+idequipo,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      },
      complete: function () {
        $(".buttons-reload").attr('data-bs-toggle', 'tooltip').attr('data-bs-original-title', 'Recargar');
        $(".buttons-copy").attr('data-bs-toggle', 'tooltip').attr('data-bs-original-title', 'Copiar');
        $(".buttons-excel").attr('data-bs-toggle', 'tooltip').attr('data-bs-original-title', 'Excel');
        $(".buttons-pdf").attr('data-bs-toggle', 'tooltip').attr('data-bs-original-title', 'PDF');
        $(".buttons-colvis").attr('data-bs-toggle', 'tooltip').attr('data-bs-original-title', 'Columnas');
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      dataSrc: function (e) {
				if (e.status != true) {  ver_errores(e); }  return e.aaData;
			},
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },
		language: {
      lengthMenu: "_MENU_ ",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[0, "asc"]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [4], visible: false, searchable: false, },   
      { targets: [5], visible: false, searchable: false, }     
    ]
  }).DataTable();
}




function mostrar_titulo(){
  $("#modal-agregar-titulo").modal("show");
  $.post("../ajax/equipo.php?op=datos_equipo", { idequipo: idequipo }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {

      $("#idequipo").val(e.data.idequipos_pi);
      $("#titulo_p").val(e.data.titulo_equipo);

    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

function mostrar_rol_estudiante(idestudiante){
  $(".tooltip").remove();
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  limpiar_form_rol();

  $("#modal-agregar-rol").modal("show");

  $.post("../ajax/equipo.php?op=mostrar_rol_estudiante", { idestudiante: idestudiante, idequipo: idequipo }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#idestudiante").val(e.data.idestudiante);
      $("#nombres").text(e.data.nombres+ ' ' +e.data.apellidos);
      $("#rol_e").val(e.data.rol_proyecto);



      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

function editar_titulo(e){
  var formData = new FormData($("#form-agregar-titulo")[0]);
  $.ajax({
		url: "../ajax/equipo.php?op=editar_titulo",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);
        if (e.status == true) {	
					sw_success('Exito', 'TÍtulo registrado correctamente.');
          limpiar_form_t();
          $("#modal-agregar-titulo").modal("hide");
          listar_equipo();
				} else {
					ver_errores(e);
				}				
			} catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51961837587" >961-837-587</a></i> ─ <i><a href="tel:+51961837587" >961-837-587</a></i>', 700); }      
      $(".btn-guardar").html('<i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar').removeClass('disabled send-data');
		},
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = (evt.loaded / evt.total) * 100;
					/*console.log(percentComplete + '%');*/
					$("#barra_progress_titulo").css({ "width": percentComplete + '%' });
					$("#barra_progress_titulo div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_titulo").css({ width: "0%", });
			$("#barra_progress_titulo div").text("0%");
      $("#barra_progress_titulo_div").show();
		},
		complete: function () {
			$("#barra_progress_titulo").css({ width: "0%", });
			$("#barra_progress_titulo div").text("0%");
      $("#barra_progress_titulo_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}

function editar_rol(e){
  var formData = new FormData($("#form-agregar-rol")[0]);
	$.ajax({
		url: "../ajax/equipo.php?op=editar_rol",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);
        if (e.status == true) {	
					sw_success('Exito', 'Rol registrado correctamente.');
          limpiar_form_rol();
          tabla_equipo.ajax.reload(null, false);
          $("#modal-agregar-rol").modal("hide");  
				} else {
					ver_errores(e);
				}				
			} catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51961837587" >961-837-587</a></i> ─ <i><a href="tel:+51961837587" >961-837-587</a></i>', 700); }      
      $(".btn-guardar").html('<i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar').removeClass('disabled send-data');
		},
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = (evt.loaded / evt.total) * 100;
					/*console.log(percentComplete + '%');*/
					$("#barra_progress_rol").css({ "width": percentComplete + '%' });
					$("#barra_progress_rol div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_rol").css({ width: "0%", });
			$("#barra_progress_rol div").text("0%");
      $("#barra_progress_rol_div").show();
		},
		complete: function () {
			$("#barra_progress_rol").css({ width: "0%", });
			$("#barra_progress_rol div").text("0%");
      $("#barra_progress_rol_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}

// ::::::::::::::::::::::::: F O R M   V A L I D A T I O N :::::::::::::::::::::::::
$(function () {
  $("#form-agregar-rol").validate({
    ignore: "",
    rules: {
      rol_e:  { required: true, minlength: 2, maxlength: 30, },   
    },
    messages: {     
      rol_e:  { required: "Campo requerido", minlength: "El rol debe tener minimo 2 caracteres", maxlength: "El rol debe tener maximo 30 caracteres", },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      editar_rol(e);      
    },
  });

  $("#form-agregar-titulo").validate({
    ignore: "",
    rules: {
      titulo_p:  { required: true, minlength: 2, maxlength: 50, },
    },
    messages: {     
      titulo_p:  { required: "Campo requerido", minlength: "El título debe tener minimo 2 caracteres", maxlength: "El título debe tener maximo 50 caracteres", },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      editar_titulo(e);      
    },
  });
});



// :::::::::::::::::::::::::: F U N C I O N E S   A D I C I O N A L E S  :::::::::::::::::::::::::::
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

$(document).ready(function () {
  init();
});