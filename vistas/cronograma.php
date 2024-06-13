<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  date_default_timezone_set('America/Lima'); require "../config/funcion_general.php";
  session_start();
  if (!isset($_SESSION["user_nombre"])){
    header("Location: index.php");
  }else {
    ?>
      <!DOCTYPE html>
      <html lang="es" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="icon-overlay-close">

        <head>
          
          <?php $title_page = "Cromograma"; include("template/head.php"); ?>
          <!-- Quill Editor CSS -->
          <link rel="stylesheet" href="../assets/libs/quill/quill.snow.css">
          <link rel="stylesheet" href="../assets/libs/quill/quill.bubble.css">
          
        </head> 

        <body id="body-usuario" idusuario="<?php echo $_SESSION['idusuario']; ?>"  >

          <?php include("template/switcher.php"); ?>
          <?php include("template/loader.php"); ?>

          <div class="page">
            <?php include("template/header.php") ?>
            <?php include("template/sidebar.php") ?>
            <?php if($_SESSION['cronograma'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn-modal-effect btn btn-primary label-btn btn-agregar m-r-10px" onclick="limpiar_actividad(); asignar_fecha_inicio();" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" data-bs-target="#modal-agregar-actividad"  > <i class="ri-edit-line label-btn-icon me-2"></i>Agregar</button>
                      <button type="button" class="btn btn-danger btn-cancelar m-r-10px" onclick="show_hide_entregable(1);" style="display: none;"><i class="ri-arrow-left-line"></i></button>
                      <button class="btn-modal-effect btn btn-success label-btn btn-subir m-r-10px" onclick="limpiar_form_entrg();" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" data-bs-target="#modal-agregar-entregable" style="display: none;"  > <i class="ri-upload-line label-btn-icon me-2"></i>Subir </button>
                      <div>
                        <p class="fw-semibold fs-18 mb-0">Cromograma de Actividades</p>
                        <span class="fs-semibold text-muted">Gestiona adecuadamente las actividades 📒</span>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Proyecto</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cromograma</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    
                    <div class="card custom-card"> 
                                      
                      <div class="card-body table-responsive">
                        <!-- CROMOGRAMA -->
                        <div class="row" id="div-cromog">

                          <table id="tabla-crono" class="table table-bordered w-100" style="width: 100%;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Acciones</th>
                                <th>Actividad</th>
                                <th>Descripción</th>
                                <th>Duración</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Cierre</th>
                                <th class="text-center">Entregable</th>

                                <th>Actividad</th>
                                <th>Descripción</th>
                                <th>Duración</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                              <th class="text-center">#</th>
                                <th class="text-center">Acciones</th>
                                <th>Actividad</th>
                                <th>Descripción</th>
                                <th>Duración</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Cierre</th>
                                <th class="text-center">Entregable</th>

                                <th>Actividad</th>
                                <th>Descripción</th>
                                <th>Duración</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>

                        <!-- ENTREGABLES -->
                        <div class="row" id="div-entregable" style="display: none;">
                          <div id="div-galeria-archivo">
                            <div class="col-12 g_archivo">
                              <div class="card card-primary">
                                
                                <div class="card-body">
                                  <div class="row archivos_galeria text-center"> 
                                    <div class="col-lg-12 text-center" id="cargando">
                                      <i class="fas fa-spinner fa-pulse fa-6x"></i><br /> <br />  <h4>Cargando...</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-12 sin_archivo" style="display: none;">
                              <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);" >
                                <!-- agregando -->
                                <div class="alert alert-warning alert-dismissible alerta">
                                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGUNA ARCHIVOS EN ESTA ACTIVIDAD
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                          
                                            
                      </div>  
                      <div class="card-footer border-top-0">
                        <!-- FOOTER DEL CARD -->
                      </div>                 
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->           
                </div>
                <!-- End::row-1 -->


                <!-- MODAL:: REGISTRAR ACTIVIDAD - charge 1 -->
                <div class="modal fade modal-effect" id="modal-agregar-actividad" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-actividadLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="modal-agregar-actividadLabel1">Actividad</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form name="form-agregar-actividad" id="form-agregar-actividad" method="POST" class="needs-validation" novalidate>
                          <div class="row" id="cargando-1-fomulario">
                            <!-- VALORES DEL FORMULARIO - MODAL -->
                            <input type="hidden" name="idcromograma_tareas" id="idcromograma_tareas"/>
                            <input type="hidden" name="idperfil" id="idperfil"/>
                            <div class="col-md-8">
                              <div class="form-group">
                                <label for="nombre_a" class="form-label">Actividad(*)</label>
                                <input type="text" class="form-control" name="nombre_a" id="nombre_a" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="duracion_a" class="form-label">Duración(*)</label>
                                <input type="number" class="form-control" name="duracion_a" id="duracion_a" onclick="delay(function(){asignar_fecha_cierre()}, 100 );" />
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="descr_a" class="form-label">Descripción(*)</label>
                                <textarea name="descr_a" id="descr_a" rows="2" class="form-control"></textarea>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="fecha_i_a" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_i_a" id="fecha_i_a" readonly/>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="fecha_e_a" class="form-label">Fecha Entrega</label>
                                <input type="date" class="form-control" name="fecha_e_a" id="fecha_e_a" readonly/>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="es_entreg_a" class="form-label">Es estregable</label>
                                <div class="toggle on es_entreg_a" onclick="delay(function(){es_entregable()}, 100 );"> <span></span> </div>
                                <input type="hidden" class="form-control" id="es_entreg_a" name="es_entreg_a" value="1">
                              </div>
                            </div>
                          </div>
                          <div class="row" id="cargando-2-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                              <h4 class="bx-flashing">Cargando...</h4>
                            </div>
                          </div>
                          <button type="submit" style="display: none;" id="submit-form-actividad">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" onclick="limpiar_actividad();"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_actividad"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-actividad -->


                <!-- MODAL:: REGISTRAR ENTREGABLE - charge 2 -->
                <div class="modal fade modal-effect" id="modal-agregar-entregable" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-entregableLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="modal-agregar-entregableLabel1">entregable</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form name="form-agregar-entregable" id="form-agregar-entregable" method="POST" class="needs-validation" novalidate>
                          <div class="row" id="cargando-3-fomulario">
                            <!-- VALORES DEL FORMULARIO - MODAL -->
                             <input type="hidden" name="idcrono" id="idcrono"/>
                             <label for="doc" class="form-label">Archivo</label>
                            <input class="form-control" type="file" id="doc" name="doc">
                          </div>
                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                              <h4 class="bx-flashing">Cargando...</h4>
                            </div>
                          </div>
                          <button type="submit" style="display: none;" id="submit-form-entregable">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" onclick="limpiar_form_entrg()"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_entregable"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-actividad -->
                    
                <!-- MODAL:: REGISTRAR ENTREGABLE - charge 2 -->
                <div class="modal fade modal-effect" id="modal_ver_archivo" role="dialog" tabindex="-1" aria-labelledby="modal_ver_archivoLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="modal_ver_archivoLabel1">Entregable</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" id="archivo">
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="las la-times"></i> Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-actividad -->

              </div>
            </div>

            <?php } else { $title_submodulo ='perfil'; $descripcion ='Perfil del PI'; $title_modulo = 'proyecto'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <script src="scripts/cronograma.js"></script>

          <!-- Quill Editor JS -->
          <!-- <script src="../assets/libs/quill/quill.min.js"></script> -->
          <!-- Internal Quill JS -->
          <!-- <script src="../assets/js/quill-editor.js"></script> -->

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
