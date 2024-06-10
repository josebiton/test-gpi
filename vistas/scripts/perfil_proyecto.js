var idusuario;
var idcarrera;
var idsemestre;
var idequipo;

var tabla_hitos1;
var tabla_hitos;

function init(){
	traer_data_filtro();
  mostrar_perfil_p();
  $(".btn-guardar").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-perfil-pi").submit(); }  });
  $("#guardar_registro_hito").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-hito").submit(); }  });
  listar_tabla_hitos();
	listar_hitos();
}

function limpiar_form_perfil (){
	$('#titulo_p').val('');
	$('#descripcion_p').val('');
	$('#fecha_i').val('');
	$('#fecha_e').val('');
	$('#link_p').val('');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function limpiar_hito(){

}

function show_hide_form(flag) {
	if (flag == 1) {
		$("#div-perfil").show();
		$("#div-form").hide();

		$(".btn-agregar").show();
		$(".btn-guardar").hide();
		$(".btn-cancelar").hide();
		
	} else if (flag == 2) {
		$("#div-banner").hide();
		$("#div-perfil").hide();
		$("#div-form").show();

		$(".btn-agregar").hide();
		$(".btn-guardar").show();
		$(".btn-cancelar").show();
	}
}

// ::::::::::::::::::::::::: P R I M E R A   P A G I N A :::::::::::::::::::::::::
function mostrar_perfil_p(){
	$.post("../ajax/perfil_proyecto.php?op=mostrar_perfil_p", { idequipo: idequipo }, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      if(e.data == null){
        $("#div-banner").show();
        $("#div-perfil").hide();

      } else {
        $('#nombre_p').text(e.data.titulo_proyecto);
      
        // Formatear las fechas
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

        // Crear fechas en la zona horaria local
        const fechaInicioParts = e.data.fecha_inicio.split('-');
        const fechaInicio = new Date(fechaInicioParts[0], fechaInicioParts[1] - 1, fechaInicioParts[2]);
        const fechaCierreParts = e.data.fecha_cierre.split('-');
        const fechaCierre = new Date(fechaCierreParts[0], fechaCierreParts[1] - 1, fechaCierreParts[2]);

        // Convertir a cadena con formato
        const fechaInicioFormateada = fechaInicio.toLocaleDateString('es-ES', options);
        const fechaCierreFormateada = fechaCierre.toLocaleDateString('es-ES', options);

        $('#f_ini').text(fechaInicioFormateada);
        $('#f_cie').text(fechaCierreFormateada);

        $('#descp_p').html(e.data.descripcion_proyecto);
        $('#link_prototipo').attr('href', e.data.link_prototipo);
      }
			
			
      
    } else {
      
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );
}

function listar_hitos(){
	tabla_hitos1 = $('#tabla-hitos-1').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-4'B><'col-md-2 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-2 btn btn-sm btn-outline-info btn-wave ", action: function ( e, dt, node, config ) { if (tabla_hitos1) { tabla_hitos1.ajax.reload(null, false); } } },
      { extend: 'copy', exportOptions: { columns: [0,1,2,3], }, text: `<i class="fas fa-copy" ></i>`, className: "px-2 btn btn-sm btn-outline-dark btn-wave ", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [0,1,2,3], }, title: 'Lista de Hitos', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-2 btn btn-sm btn-outline-success btn-wave ", footer: true,  }, 
      { extend: 'pdf', exportOptions: { columns: [0,1,2,3], }, title: 'Lista de Hitos', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-2 btn btn-sm btn-outline-danger btn-wave ", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-2 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: '../ajax/perfil_proyecto.php?op=tabl_hitos&idequipo='+idequipo,
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
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
    },
		language: {
      lengthMenu: "_MENU_ ",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[0, "asc"]]//Ordenar (columna,orden)
  }).DataTable();

}



// ::::::::::::::::::::::::: S E G U N D A   P A G I N A :::::::::::::::::::::::::
function mostrar_perfil_p_edit(){
	$.post("../ajax/perfil_proyecto.php?op=mostrar_perfil_p", { idequipo: idequipo }, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {

			$('#titulo_p').val(e.data.titulo_proyecto);
			$('#fecha_i').val(e.data.fecha_inicio);
			$('#fecha_e').val(e.data.fecha_cierre);
			$('#fecha_e').val(e.data.fecha_cierre);
			$(".ql-editor").html(e.data.descripcion_proyecto); 
			$('#link_p').val(e.data.link_prototipo);
		
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );
}

function listar_tabla_hitos(){
  tabla_hitos = $('#tbl-hitos').dataTable({
		"ajax":	{
			url: '../ajax/perfil_proyecto.php?op=listar_tabla_hitos&idequipo='+idequipo,
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			},
      dataSrc: function (e) {
				if (e.status != true) {  ver_errores(e); }  return e.aaData;
			},
		},
    createdRow: function (row, data, ixdex) {
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },
		
    "order": [[0, "asc"]],//Ordenar (columna,orden)
	}).DataTable();
}

function editar_perfil(e) {
	transferirContenido_comentario();
	
	var formData = new FormData($("#form-perfil-pi")[0]);

	$.ajax({
		url: "../ajax/perfil_proyecto.php?op=editar_perfil&idequipo="+idequipo,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {	
					show_hide_form(1)
					sw_success('Exito', 'Perfil Actualizado correctamente.');
					mostrar_perfil_p();
          tabla_hitos1.ajax.reload(null, false);
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
					$("#barra_progress_perfil").css({ "width": percentComplete + '%' });
					$("#barra_progress_perfil div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_perfil").css({ width: "0%", });
			$("#barra_progress_perfil div").text("0%");
      $("#barra_progress_perfil_div").show();
		},
		complete: function () {
			$("#barra_progress_perfil").css({ width: "0%", });
			$("#barra_progress_perfil div").text("0%");
      $("#barra_progress_perfil_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}

function agregar_editar_hito(e){
  var formData = new FormData($("#form-agregar-hito")[0]);

	$.ajax({
		url: "../ajax/perfil_proyecto.php?op=agregar_editar_hito&idequipo="+idequipo,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {
					sw_success('Exito', 'Hito Registrado correctamente.');
          tabla_hitos.ajax.reload(null, false);
          tabla_hitos1.ajax.reload(null, false);
          $("#modal-agregar-hito").modal("hide");  
				} else {
					ver_errores(e);
				}				
			} catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51961837587" >921-305-769</a></i> ─ <i><a href="tel:+51961837587" >921-487-276</a></i>', 700); }      
      $(".btn-guardar").html('<i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar').removeClass('disabled send-data');
		},
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = (evt.loaded / evt.total) * 100;
					/*console.log(percentComplete + '%');*/
					$("#barra_progress_hito").css({ "width": percentComplete + '%' });
					$("#barra_progress_hito div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_hito").css({ width: "0%", });
			$("#barra_progress_hito div").text("0%");
      $("#barra_progress_hito_div").show();
		},
		complete: function () {
			$("#barra_progress_hito").css({ width: "0%", });
			$("#barra_progress_hito div").text("0%");
      $("#barra_progress_hito_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}

function mostrar_hito(idhitos) {
  $(".tooltip").remove();
  $("#cargando-8-fomulario").hide();
  $("#cargando-9-fomulario").show();
  
  limpiar_hito();

  $("#modal-agregar-hito").modal("show")

  $.post("../ajax/perfil_proyecto.php?op=mostrar_hito", { idhitos: idhitos }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#idhitos").val(e.data.idhitos);
      $("#idperfil_pi").val(e.data.idperfil_pi);
      $("#nombre_hito").val(e.data.titulo_hito);        
      $("#descr_hito").val(e.data.descripcion);
      $("#fecha_hito_e").val(e.data.fecha_entrega);

      $("#cargando-8-fomulario").show();
      $("#cargando-9-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}


function eliminar_hito(idhitos, titulo_hito) {

  crud_eliminar_papelera(
    "../ajax/perfil_proyecto.php?op=desactivar_hito",
    "../ajax/perfil_proyecto.php?op=eliminar_hito", 
    idhitos, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${titulo_hito}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_hitos.ajax.reload(null, false); tabla_hitos1.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}


// ::::::::::::::::::::::::: F O R M   V A L I D A T I O N :::::::::::::::::::::::::
$(function () {
  $("#form-perfil-pi").validate({
    ignore: "",
    rules: {           
      titulo_p:       { required: true, minlength: 5, maxlength: 100, },    
      fecha_i:    		{ required: true, },       
      fecha_e:    		{ required: true, },       
      link_p:    		  { required: true, },       
    },
    messages: {     
      titulo_p:       { required: "Ingrese el titulo", minlength: "El titulo debe tener minimo 5 caracteres", maxlength: "El titulo debe tener maximo 100 caracteres", },
      fecha_i:        { required: "Ingrese la fecha de inicio", },
      fecha_e:        { required: "Ingrese la fecha de fin", },
      link_p:         { required: "Ingrese el link", },
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
      editar_perfil(e);      
    },
  });

  $("#form-agregar-hito").validate({
    ignore: "",
    rules: {           
      nombre_hito:       { required: true, minlength: 2, maxlength: 50, },    
      fecha_hito_e:    		{ required: true, },       
      descr_hito:    		{ required: true, minlength: 4, maxlength: 100, },    
    },
    messages: {     
      nombre_hito:       { required: "Ingrese el Nombre", minlength: "El titulo debe tener minimo 2 caracteres", maxlength: "El titulo debe tener maximo 50 caracteres", },
      fecha_hito_e:        { required: "Ingrese la fecha de inicio", },
      descr_hito:        { required: "Campo requerido", minlength: "El titulo debe tener minimo 4 caracteres", maxlength: "El titulo debe tener maximo 100 caracteres", },
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
      agregar_editar_hito(e);      
    },
  });
});


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

function transferirContenido_comentario(){
  var comentario = document.querySelector('#editor .ql-editor').innerHTML;  // Obtiene el contenido HTML del div dentro de #editor1
  document.getElementById('descripcion_p').value = comentario;  // Coloca el contenido en el textarea
}




$(document).ready(function () {
  init();
});