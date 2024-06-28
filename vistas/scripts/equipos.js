var idusuario;
var idperiodo; //semestre
var idcurso;
var idgrupo;
var tabl_alumnos;

function init(){
  traer_data_filtro();
  lista_equipos();
  $("#guardar_registro_equipo").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-equipo").submit(); }  });
}

function show_hide_rows(flag){
  if(flag == 1){
    $('#div-lista-de-equipos').show();
    $(".btn-agregar").show();
    $(".btn-cancelar").hide();
    $('#div-asignar-equipo').hide();
    $('#div-ver-equipo').hide();
  }else if(flag == 2){
    $('#div-asignar-equipo').show();
    $(".btn-cancelar").show();
    $(".btn-agregar").hide();
    $('#div-lista-de-equipos').hide();
    $('#div-ver-equipo').hide();
    asignarCodUni();
    asignar_num_equipo();
    lista_tabla_alumnos();
  } else if(flag == 3){
    $('#div-ver-equipo').show();
    $(".btn-cancelar").show();
    $(".btn-agregar").hide();
    $('#div-lista-de-equipos').hide();
    $('#div-asignar-equipo').hide();
  }

}


function lista_equipos() {
  $.post("../ajax/equipos.php?op=listar_equipos", { idsemestre: idperiodo, idcurso: idcurso }, function (e, status) {
    e = JSON.parse(e);

    if (e.status === true) {
      e.data.forEach((val, key) => {

        $titulo = val.titulo_equipo ? val.titulo_equipo : "";
        $hitos = val.numero_hitos ? val.numero_hitos : 0;

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
                      <div>
                        <button type="button" class="btn btn-light  rounded-pill btn-wave" onclick="ver_proyecto(${val.idequipos_pi});" style="margin-bottom: 10px;">Ver Proyecto</button>
                        <button type="button" class="btn btn-light  rounded-pill btn-wave" style="margin-bottom: 10px;">Calificar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>`;

        $("#div-lista-de-equipos").append(html_equipos);

      });

    } else {
      ver_errores(e);
    }

  }).fail(function (e) {
    ver_errores(e);
  });
}


function lista_tabla_alumnos() {
  tabl_alumnos = $('#tbl-alumno').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-4'B><'col-md-2 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-2 btn btn-sm btn-outline-info btn-wave ", action: function ( e, dt, node, config ) { if (tabl_alumnos) { tabl_alumnos.ajax.reload(null, false); } } },
      { extend: 'copy', exportOptions: { columns: [], }, text: `<i class="fas fa-copy" ></i>`, className: "px-2 btn btn-sm btn-outline-dark btn-wave ", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [], }, title: 'Alumnos', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-2 btn btn-sm btn-outline-success btn-wave ", footer: true,  }, 
      { extend: 'pdf', exportOptions: { columns: [], }, title: 'Alumnos', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-2 btn btn-sm btn-outline-danger btn-wave ", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-2 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: '../ajax/equipos.php?op=lista_tabla_alumnos&idsemestre='+idperiodo+'&idcurso='+idcurso,
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
      // if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
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
      // { targets: [8], visible: false, searchable: false, },
      // { targets: [9], visible: false, searchable: false, },      
      // { targets: [10], visible: false, searchable: false, },      
    ]
  }).DataTable();
}

function ver_proyecto(idequipo){
  show_hide_rows(3);
  console.log(idequipo);
  $("#div-datos-pi").empty();

  $.post("../ajax/equipos.php?op=datos_equipo", { idequipo: idequipo }, function (e, status) {
    e = JSON.parse(e);
    if(e.status == true){

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

      var html_datos = `<div class="col-xl-2">
                          <span><b>Título: </b></span>
                        </div>
                        <div class="col-xl-10">
                          <span class="fs-16">${e.data.titulo_proyecto}</span>
                        </div>
                        <div class="col-12">
                          <hr class="my-2">
                        </div>
                        <div class="col-xl-2 m-0">
                          <span><b>Descripción: </b></span>
                        </div>
                        
                        <div class="col-xl-10 fs-14"> 
                          ${e.data.descripcion_proyecto}
                        </div>
                        <div class="col-12 m-0 p-0">
                          <hr class="my-2">
                        </div>
                        <div class="col-xl-4 text-center">
                          <span><b>Fecha Inicio: </b> <p>${fechaInicioFormateada}</p></span>
                        </div>
                        <div class="col-xl-4 text-center">
                          <span><b>Fecha Entrega: </b> <p>${fechaCierreFormateada}</p></span>
                        </div>
                        <div class="col-xl-4 text-center">
                          <span><b>Link Prototipo: </b> <p>${e.data.link_prototipo}</p></span>
                        </div>
                        <div class="col-12">
                          <hr class="my-2">
                        </div>`;

      $("#div-datos-pi").append(html_datos);

    } else{ver_errores(e);}

  }).fail( function(e) { ver_errores(e); } );


  // $.post("../ajax/equipos.php?op=hitos_equipo", { idequipo: idequipo }, function (e, status) {
  //   e = JSON.parse(e);
  //   if(e.status == true){



  //   } else{ver_errores(e);}

  // }).fail( function(e) { ver_errores(e); } );


  // $.post("../ajax/equipos.php?op=actividades_equipo", { idequipo: idequipo }, function (e, status) {
  //   e = JSON.parse(e);
  //   if(e.status == true){



  //   } else{ver_errores(e);}

  // }).fail( function(e) { ver_errores(e); } );

}


function mover_estudiante(idestudiante) {
  let studentRow = document.querySelector(`.student-row[data-idestudiante="${idestudiante}"]`);
  
  if (studentRow) {
      let clonedRow = studentRow.cloneNode(true);
      
      document.getElementById("list_select_estud").appendChild(clonedRow);
      studentRow.parentElement.parentElement.style.display = 'none';
      
      let buttons = clonedRow.querySelectorAll("button");
      buttons.forEach(button => button.remove());

      let removeButton = document.createElement('button');
      removeButton.classList.add('btn', 'btn-icon', 'btn-sm', 'btn-danger-light');
      removeButton.innerHTML = '<i class="ri-close-line"></i>';
      removeButton.onclick = function() {
          clonedRow.remove();
          studentRow.parentElement.parentElement.style.display = 'table-row';
          actualizarEstudiantesSeleccionados();
      };

      clonedRow.appendChild(removeButton);
      
      actualizarEstudiantesSeleccionados();
  }
}

function actualizarEstudiantesSeleccionados() {
  let estudiantesSeleccionados = [];
  document.querySelectorAll('#list_select_estud .student-row').forEach(row => {
      estudiantesSeleccionados.push(row.getAttribute('data-idestudiante'));
  });
  document.getElementById('estudiantes_seleccionados').value = estudiantesSeleccionados.join(',');
}

function asignar_num_equipo(){

  $.post("../ajax/equipos.php?op=ultimo_equipo", { idsemestre: idperiodo, idcurso: idcurso }, function (e, status) {

    e = JSON.parse(e);  //console.log(e);  

    if (e.status == true) {
      let equipoNombre = e.data.nombre_equipo;
      let equipoNumero = parseInt(equipoNombre.replace('Equipo-', '')) + 1;
      let nuevoNombreEquipo = 'Equipo-' + equipoNumero;

      $("#num_equipo").text(nuevoNombreEquipo);
      $("#n_equipo").val(nuevoNombreEquipo);
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

function guardar_equipo(e){
  var formData = new FormData($("#form-agregar-equipo")[0]);

	$.ajax({
		url: "../ajax/equipos.php?op=guardar_equipo",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try {
				e = JSON.parse(e);
        if (e.status == true) {	
					sw_success('Exito', 'Equipo registrado correctamente.');
          lista_tabla_alumnos();
          $("#list_select_estud").html('');
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
					$("#barra_progress_equipo").css({ "width": percentComplete + '%' });
					$("#barra_progress_equipo div").text(percentComplete.toFixed(2) + " %");
				}
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$(".btn-guardar").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_equipo").css({ width: "0%", });
			$("#barra_progress_equipo div").text("0%");
      $("#barra_progress_equipo_div").show();
		},
		complete: function () {
			$("#barra_progress_equipo").css({ width: "0%", });
			$("#barra_progress_equipo div").text("0%");
      $("#barra_progress_equipo_div").hide();
		},
		error: function (jqXhr, ajaxOptions, thrownError) {
			ver_errores(jqXhr);
		}
	});
}


// ::::::::::::::::::::::::: F O R M   V A L I D A T I O N :::::::::::::::::::::::::
$(function () {
  $("#form-agregar-equipo").validate({
    ignore: "",
    rules: {               
    },
    messages: {     
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600);
      guardar_equipo(e);      
    },
  });

});



function generarCodUni(longitud) {
  const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:,.<>?';
  let codigo = '';
  for (let i = 0; i < longitud; i++) {
      const randomIndex = Math.floor(Math.random() * caracteres.length);
      codigo += caracteres.charAt(randomIndex);
  }
  return codigo;
}

function asignarCodUni() {
  const codigo = generarCodUni(10); // Cambia el número 10 a la longitud que prefieras
  document.getElementById('codigo_equipo').value = codigo;
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


$(document).ready(function () {
  init();
});