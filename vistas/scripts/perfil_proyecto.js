var tabla_hitos;
var idusuario = document.getElementById('user_e').value;

function init(){

  $(".btn-guardar").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-perfil-pi").submit(); }  });

  listar_tabla_hitos();
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

function show_hide_form(flag) {
	if (flag == 1) {
		$("#div-perfil").show();
		$("#div-form").hide();

		$(".btn-agregar").show();
		$(".btn-guardar").hide();
		$(".btn-cancelar").hide();
		
	} else if (flag == 2) {
		$("#div-perfil").hide();
		$("#div-form").show();

		$(".btn-agregar").hide();
		$(".btn-guardar").show();
		$(".btn-cancelar").show();
	}
}

function mostrar_perfil_p(idusuario){
	$.post("../ajax/perfil_proyecto.php?op=mostrar_perfil_p", { idusuario: idusuario }, function (e, status) {
		e = JSON.parse(e);
    if (e.status == true) {
      $('#nombre_p').val(e.data.titulo_proyecto);
      
    } else {
      ver_errores(e)
    }
	}).fail( function(e) { ver_errores(e); } );
}

function listar_tabla_hitos(){
  tabla_hitos = $('#tbl-hitos').dataTable({
		"ajax":	{
			url: '../ajax/perfil_proyecto.php?op=listar_tabla_hitos&idusuario='+idusuario,
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
	//e.preventDefault(); //No se activará la acción predeterminada del evento
	
	var formData = new FormData($("#form-perfil-pi")[0]);

	$.ajax({
		url: "../ajax/perfil_proyecto.php?op=editar_perfil&idusuario="+idusuario,
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
				} else {
					ver_errores(e);
				}				
			} catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
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


$(document).ready(function () {
  init();
});

$(function () {
  $("#form-perfil-pi").validate({
    ignore: "",
    rules: {           
      titulo_p:       { required: true, minlength: 5, maxlength: 100, },       
      descripcion_p:  { required: true, minlength: 8, maxlength: 500, },       
      fecha_i:    		{ required: true, },       
      fecha_e:    		{ required: true, },       
      link_p:    		  { required: true, },       
    },
    messages: {     
      titulo_p:       { required: "Ingrese el titulo", minlength: "El titulo debe tener minimo 5 caracteres", maxlength: "El titulo debe tener maximo 100 caracteres", },
      descripcion_p:  { required: "Ingrese la descripcion", minlength: "La descripcion debe tener minimo 8 caracteres", maxlength: "La descripcion debe tener maximo 500 caracteres", },
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
});


