var idusuario;
var idcarrera;
var idsemestre;
var idequipo;

var tabla_cronograma;

function init(){

  traer_data_filtro();
  tabla_principal_crono();
  $("#guardar_registro_actividad").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-actividad").submit(); }  });
  $("#guardar_registro_entregable").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-entregable").submit(); }  });

}

function show_hide_entregable(flag) {
	if (flag == 1) {
		$("#div-cromog").show();
		$("#div-entregable").hide();

		$(".btn-agregar").show();
		$(".btn-subir").hide();
		$(".btn-cancelar").hide();
		
	} else if (flag == 2) {
		$("#div-cromog").hide();
		$("#div-entregable").show();

		$(".btn-agregar").hide();
		$(".btn-subir").show();
		$(".btn-cancelar").show();
	}
}

function limpiar_actividad(){
  $("#idcromograma_tareas").val('');
  $("#nombre_a").val('');
  $("#duracion_a").val('');
  $("#descr_a").val('');
  $("#fecha_e_a").val('');
  $("#es_entreg_a").val('');
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function limpiar_form_entrg(){
  $("#doc").val("");
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}


// ::::::::::::::::::::::::: C R O N O G R A M A  :::::::::::::::::::::::::
function tabla_principal_crono() {

  tabla_cronograma = $('#tabla-crono').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-4'B><'col-md-2 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-2 btn btn-sm btn-outline-info btn-wave ", action: function ( e, dt, node, config ) { if (tabla_cronograma) { tabla_cronograma.ajax.reload(null, false); } } },
      { extend: 'copy', exportOptions: { columns: [0,8,9,10,5,6], }, text: `<i class="fas fa-copy" ></i>`, className: "px-2 btn btn-sm btn-outline-dark btn-wave ", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [0,8,9,10,5,6], }, title: 'Cronograma', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-2 btn btn-sm btn-outline-success btn-wave ", footer: true,  }, 
      { extend: 'pdf', exportOptions: { columns: [0,8,9,10,5,6], }, title: 'Cronograma', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-2 btn btn-sm btn-outline-danger btn-wave ", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-2 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: '../ajax/cronograma.php?op=tabla_principal_crono&idequipo='+idequipo,
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
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
      if (data[7] != '') { $("td", row).eq(7).addClass("text-center"); }
    },
		language: {
      lengthMenu: "_MENU_ ",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 20,//Paginación
    "order": [[0, "asc"]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [8], visible: false, searchable: false, },
      { targets: [9], visible: false, searchable: false, },      
      { targets: [10], visible: false, searchable: false, },      
    ]
  }).DataTable();
}


function guardar_editar_crono(e){
  es_entregable();
  var formData = new FormData($("#form-agregar-actividad")[0]);

	$.ajax({
		url: "../ajax/cronograma.php?op=guardar_editar_crono&idequipo="+idequipo,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);
        if (e.status == true) {	
					sw_success('Exito', 'Actividad registrado correctamente.');
          tabla_cronograma.ajax.reload(null, false);
          limpiar_actividad();
          $("#modal-agregar-actividad").modal("hide");  
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
					$("#barra_progress_crono").css({ "width": percentComplete + '%' });
					$("#barra_progress_crono div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_crono").css({ width: "0%", });
			$("#barra_progress_crono div").text("0%");
      $("#barra_progress_crono_div").show();
		},
		complete: function () {
			$("#barra_progress_crono").css({ width: "0%", });
			$("#barra_progress_crono div").text("0%");
      $("#barra_progress_crono_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}

function mostrar_crono(idcromograma_tareas) {
  $(".tooltip").remove();
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  limpiar_actividad();

  $("#modal-agregar-actividad").modal("show");

  $.post("../ajax/cronograma.php?op=mostrar_crono", { idcromograma_tareas: idcromograma_tareas }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#idcromograma_tareas").val(e.data.idcromograma_tareas);
      $("#idperfil").val(e.data.idperfil_pi);
      $("#nombre_a").val(e.data.nombre_actividad);
      $("#duracion_a").val(e.data.dia_duracion).prop("readonly", true);
      $("#descr_a").val(e.data.descripcion_actividad);
      $("#fecha_i_a").val(e.data.fecha_inicio);
      $("#fecha_e_a").val(e.data.fecha_cierre);
      $("#es_entreg_a").val(e.data.es_entregable);

      if(e.data.es_entregable == 1){
        $(".es_entreg_a").addClass("on");
      }else{
        $(".es_entreg_a").removeClass("on");
      }


      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_crono(idcromograma_tareas, nombre_actividad) {

  crud_eliminar_papelera(
    "../ajax/cronograma.php?op=desactivar_crono",
    "../ajax/cronograma.php?op=eliminar_crono", 
    idcromograma_tareas, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre_actividad}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_cronograma.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

function asignar_fecha_inicio(){
  $.post("../ajax/cronograma.php?op=traer_fecha_crono", { idequipo: idequipo }, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {

      let fecha1 = e.data.fecha1;
      let fecha = new Date(fecha1);
      fecha.setDate(fecha.getDate() + 1);
      let nuevaFecha1 = fecha.toISOString().split('T')[0]; // Formato YYYY-MM-DD

      $("#fecha_i_a").val(nuevaFecha1);
      console.log(nuevaFecha1);
      
    } else {
      
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );
}


function asignar_fecha_cierre(){
  var fecha_inicio = $("#fecha_i_a").val(); // Ejemplo: 2024-06-05
  var duracion = parseInt($("#duracion_a").val(), 10); // Ejemplo: 2

  // Crear un objeto de fecha a partir de la fecha de inicio
  var fecha = new Date(fecha_inicio);

  // Sumar la duración a la fecha de inicio
  fecha.setDate(fecha.getDate() + duracion);

  // Formatear la nueva fecha en el formato yyyy-mm-dd
  var dia = String(fecha.getDate()).padStart(2, '0');
  var mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses comienzan en 0
  var año = fecha.getFullYear();

  var fecha_cierre = `${año}-${mes}-${dia}`;

  // Asignar la fecha de cierre al campo correspondiente
  $("#fecha_e_a").val(fecha_cierre);
}

$("#duracion_a").change(function() {
  asignar_fecha_cierre();
});


// ::::::::::::::::::::::::: E N T R E G A B L E  :::::::::::::::::::::::::
function mostrar_archivos(idcrono){
  $('.archivos_galeria').html('');
  $.post("../ajax/cronograma.php?op=mostrar_archivos",{idcrono: idcrono}, function (e, status) {
    e = JSON.parse(e);  console.log(e);    
    if (e.status == true) {
      if (e.data == null || e.data.length == 0) {
        $(".g_archivo").hide(); $(".sin_archivo").show();
      }else{
        $(".sin_archivo").hide(); $(".g_archivo").show();    
        $("#cargando").hide();
        $('#archivo').attr('src', '');
        
        e.data.forEach((val, key) => {
          var galeria_html = `<div class="col-sm-2 text-center px-1 py-1 b-radio-5px" style=" margin-right: 1cm;" > 
            <a href="#" onclick="ver_archivo_e(${val.identregables})">
              <img src="../assets/modulo/cronograma/${val.nombre_archivo}" class="img-fluid mb-2 b-radio-t-5px" alt="white sample" style="object-fit: cover !important; height:200px !important;" onerror="this.src='../assets/modulo/cronograma/default_crono.png';"/>
            </a>

          </div> `;
          $('.archivos_galeria').append(galeria_html);
          
        }); 

      }
      $('.jq_image_zoom').zoom({ on:'grab' });     
      
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

function ver_archivo_e(identregables){
  
  $.post("../ajax/cronograma.php?op=mostrar_archivo",{identregables: identregables}, function (e, status) {
    e = JSON.parse(e);  console.log(e);    
    if (e.status == true) {
      $("#modal_ver_archivo").modal('show');
      var filename = e.data.nombre_archivo; var width = '100%'; var height = 'auto';
      var ruta = "../assets/modulo/cronograma/";

      
      if ( extrae_extencion(filename) == "xls" ||
       extrae_extencion(filename) == "xlsx" ||
       extrae_extencion(filename) == "csv" ||
       extrae_extencion(filename) == "xlsm" ||
       extrae_extencion(filename) == "xlsb" ||
       extrae_extencion(filename) == "docx" ||
       extrae_extencion(filename) == "docm" ||
       extrae_extencion(filename) == "dot" ||
       extrae_extencion(filename) == "dotx" ||
       extrae_extencion(filename) == "dotm" ||
       extrae_extencion(filename) == "doc" ||
       extrae_extencion(filename) == "dwg" ||
       extrae_extencion(filename) == "zip" ||
       extrae_extencion(filename) == "rar" ||
       extrae_extencion(filename) == "iso") {
    $("#archivo").html(`<img src="${ruta}${filename}" alt="" width="10px" height="10px">`);
  } else if ( extrae_extencion(filename) == "pdf" || extrae_extencion(filename) == "PDF" ) {
    $("#archivo").html(`<iframe src="${ruta}${filename}" frameborder="0" scrolling="no" width="${width}" height="500px"></iframe>`);
  } else if ( extrae_extencion(filename) == "pfx" || extrae_extencion(filename) == "p12" ) {
    $("#archivo").html(`<img src="../assets/img/default/pfx.jpg" alt="" width="50%">`);
  } else if ( extrae_extencion(filename) == "jpeg" || extrae_extencion(filename) == "jpg" || extrae_extencion(filename) == "jpe" ||
              extrae_extencion(filename) == "jfif" || extrae_extencion(filename) == "gif" || extrae_extencion(filename) == "png" ||
              extrae_extencion(filename) == "tiff" || extrae_extencion(filename) == "tif" || extrae_extencion(filename) == "webp" ||
              extrae_extencion(filename) == "bmp" || extrae_extencion(filename) == "svg" ) {
    $("#archivo").html(`<center><img src="${ruta}${filename}" alt="" width="600px" height="auto" onerror="this.src='../assets/svg/404-v2.svg';"></center>`);
  } else {
    $("#archivo").html(`<img src="../assets/svg/doc_si_extencion.svg" alt="" width="50%" height="50%">`);
  }
      
      
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

function guardar_entrg(e){
  $idcrono = $("#idcrono").val();
  var formData = new FormData($("#form-agregar-entregable")[0]);
	$.ajax({
		url: "../ajax/cronograma.php?op=guardar_doc",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);
        if (e.status == true) {	
					sw_success('Exito', 'Archivo guardado correctamente.');
          limpiar_form_entrg();
          mostrar_archivos($idcrono);
          $("#modal-agregar-entregable").modal("hide");  
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
					$("#barra_progress_entrg").css({ "width": percentComplete + '%' });
					$("#barra_progress_entrg div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_entrg").css({ width: "0%", });
			$("#barra_progress_entrg div").text("0%");
      $("#barra_progress_entrg_div").show();
		},
		complete: function () {
			$("#barra_progress_entrg").css({ width: "0%", });
			$("#barra_progress_entrg div").text("0%");
      $("#barra_progress_entrg_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}





// ::::::::::::::::::::::::: F O R M   V A L I D A T I O N :::::::::::::::::::::::::
$(function () {
  $("#form-agregar-actividad").validate({
    ignore: "",
    rules: {           
      nombre_a:   { required: true, minlength: 2, maxlength: 50, },    
      duracion_a: { required: true, min: 1, max: 15 },
      descr_a:    { required: true, minlength: 4, maxlength: 100, },
      fecha_i_a:  { required: true, },
      fecha_e_a:  { required: true, },     
    },
    messages: {     
      nombre_a:       { required: "Campo requerido", minlength: "El nombre debe tener minimo 2 caracteres", maxlength: "El nombre debe tener maximo 50 caracteres", },
      duracion_a:     { required: "Campo requerido", min: "minimo 1 día", max: "maximo 15" },
      descr_a:        { required: "Campo requerido", minlength: "Minimo 4 caracteres", maxlength: "Maximo 100 caracteres", },
      fecha_i_a:      { required: "Campo requerido", },
      fecha_e_a:      { required: "Campo requerido", },
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
      guardar_editar_crono(e);      
    },
  });

  $("#form-agregar-entregable").validate({
    ignore: "",
    rules: {
      doc:  { required: true, },   
    },
    messages: {     
      doc:  { required: "Campo requerido", },
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
      guardar_entrg(e);      
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

function es_entregable() { 

  if ($(".es_entreg_a").hasClass("on") == true) {

    $("#es_entreg_a").val("1");
    
  } else {
    $("#es_entreg_a").val("0");
  }
  $valor = $("#es_entreg_a").val();
  console.log($valor);
}

function enviar_idcrono($idcrono) {
  $("#idcrono").val($idcrono);
  console.log($idcrono);
}



$(document).ready(function () {
  init();
});