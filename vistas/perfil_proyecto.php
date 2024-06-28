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
          
          <?php $title_page = "Perfil PI"; include("template/head.php"); ?>
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
            <?php if($_SESSION['perfil'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn-modal-effect btn btn-primary label-btn btn-agregar m-r-10px" onclick="show_hide_form(2);  limpiar_form_perfil(); mostrar_perfil_p_edit();"  > <i class="ri-edit-line label-btn-icon me-2"></i>Editar </button>
                      <button type="button" class="btn btn-danger btn-cancelar m-r-10px" onclick="mostrar_perfil_p(); show_hide_form(1);" style="display: none;"><i class="ri-arrow-left-line"></i></button>
                      <button class="btn-modal-effect btn btn-success label-btn btn-guardar m-r-10px" style="display: none;"> <i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar </button>
                      <div>
                        <p class="fw-semibold fs-18 mb-0">Perfil del Proyecto Integrador</p>
                        <span class="fs-semibold text-muted">Gestiona adecuadamente tu proyecto &#128521;</span>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">PROYECTO</a></li>
                        <li class="breadcrumb-item active" aria-current="page">perfil</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    
                    <div class="card custom-card"> 
                                      
                      <div class="card-body">
                        <!-- SIN DATOS -->
                         <div class="row" id="div-banner" style="display: none;">
                          <div class="alert alert-warning text-center" role="alert">
                            <h4 class="text-warning">
                              Empiesa a Redactar tu Proyecto
                              <button class="btn btn-icon btn-warning-transparent btn-wave" onclick="show_hide_form(2);  limpiar_form_perfil(); mostrar_perfil_p_edit();"  > <i class="ri-edit-line"></i></button>
                            </h4>
                          </div>
                         </div>
                        <!-- PORTADA -->
                        <div class="row" id="div-perfil">

                          <div class="col-xl-12 d-block p-2 bg-secondary-transparent mb-2 rounded fs-4 text-center">
                            <b><span id="nombre_p"></span></b>
                          </div>
                          <div class="col-xl-3 d-block p-2 bg-primary-transparent mx-2 mb-2 fs-6 rounded">
                            <span><b>Inicio</b>: <p id="f_ini"></p></span>
                            <span><b>Entrega</b>: <p id="f_cie"></p></span>
                          </div>
                          <div class="col-xl-8 d-block bg-primary-transparent rounded mb-2" style="width: 73%;">
                            <div name="descp_p" id="descp_p" class=" mx-3 my-3"></div>
                          </div>
                          <div class="col-12 p-1"></div>
                          <div class="col-xl-3 d-block p-2 mx-2 bg-secondary-transparent rounded mb-2 text-center">
                            <a id="link_prototipo" target="_blank" href="">
                              <img src="../assets/images/default/f2.png" alt="" style="width: 70%;">
                            </a>
                          </div>

                          <div class="col-xl-8 p-2 bg-light rounded" style="width: 73%;">
                                <span class="text-dark fs-6 my-2 mx-3"><b>Hitos del Proyecto</b></span>
                            <div class="card custom-card">
                              <div class="card-body table-responsive">
                                <table id="tabla-hitos-1" class="table text-nowrap w-100" style="width: 100%;">
                                  <thead>
                                    <tr>
                                      <th class="text-center">#</th>
                                      <th class="text-center">Hito</th>
                                      <th>Descripción</th>
                                      <th>Fecha Entrega</th>
                                      <th class="text-center">Estado</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>
                              </div>
                            </div>
                          </div>


                        </div>

                        <!-- FORMULARIO -->
                        <div class="row" id="div-form" style="display: none;">
                          <form name="form-perfil-pi" id="form-perfil-pi" method="POST" class="needs-validation" novalidate>

                            <div class="row gy-4" id="cargando-1-fomulario">

                              <div class="col-md-8">
                                <div class="form-label">
                                  <label for="titulo_p" class="form-label">Titulo del Proyecto(*)</label>
                                  <input class="form-control" name="titulo_p" id="titulo_p" />
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="fecha_i" class="form-label">Fecha Inicio(*)</label>
                                  <input type="date" class="form-control" name="fecha_i" id="fecha_i" />
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="fecha_e" class="form-label">Fecha Entrega(*)</label>
                                  <input type="date" class="form-control" name="fecha_e" id="fecha_e" />
                                </div>
                              </div>
                              <div class="col col-xl-12 col-md-12 h-50 ">
                                <div id="editor"> </div>
                                <textarea name="descripcion_p" id="descripcion_p" class="hidden"></textarea>
                              </div>

                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="link_p" class="form-label">Link del Prototipo</label>
                                <input class="form-control" type="url" id="link_p" name="link_p" placeholder="http://example.com">
                              </div>

                            </div>
                            
                            <br><br>

                            <!-- cargando... -->
                            <div class="row" id="cargando-3-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">                         
                                <div class="spinner-border me-4" style="width: 3rem; height: 3rem;"role="status"></div>
                                <h4 class="bx-flashing">Cargando...</h4>
                              </div>
                            </div>

                            <!-- Chargue -->
                            <div class="p-l-25px col-lg-12" id="barra_progress_perfil_pi_div" style="display: none;" >
                              <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                                <div id="barra_progress_perfil_pi" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                              </div>
                            </div>
                            <!-- Submit -->
                            <button type="submit" style="display: none;" id="submit-form-perfil-pi">Submit</button>

                          </form>
                          <div class="row gy-2" id="cargando-2-fomulario">

                            <div class="col-xl-12">
                              <label for="hitos">
                                <b class="m-3">Lista de Hitos</b>
                                <button class=" btn-modal-effect btn btn-primary-light btn-sm" onclick="limpiar_hito();" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" data-bs-target="#modal-agregar-hito">Agregar</button>
                              </label>
                              <table id="tbl-hitos" class="table table-bordered w-100" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                    <th scope="col">Hito</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Fecha de Entrega</th>
                                    <th scope="col" class="text-center">estado</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                          
                                            
                      </div>  
                      <div class="card-footer border-top-0">
                        <button type="button" class="btn btn-danger btn-cancelar" onclick="show_hide_form(1);" style="display: none;"><i class="las la-times fs-lg"></i> Cancelar</button>
                        <button class="btn-modal-effect btn btn-success label-btn btn-guardar m-r-10px" style="display: none;"  > <i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar </button>
                      </div>                 
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->           
                </div>
                <!-- End::row-1 -->


                <!-- MODAL:: REGISTRAR HITO - charge 1 -->
                <div class="modal fade modal-effect" id="modal-agregar-hito" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-pagoLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="modal-agregar-hitoLabel1">Hito del Proyecto</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form name="form-agregar-hito" id="form-agregar-hito" method="POST" class="needs-validation" novalidate>
                          <div class="row" id="cargando-8-fomulario">
                            <input type="hidden" name="idhitos" id="idhitos">
                            <input type="hidden" name="idperfil_pi" id="idperfil_pi">

                            <div class="col-md-8">
                              <div class="form-label">
                                <label for="nombre_hito" class="form-label">Nombre(*)</label>
                                <input class="form-control" name="nombre_hito" id="nombre_hito" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="fecha_hito_e" class="form-label">Fecha Entrega(*)</label>
                                <input type="date" class="form-control" name="fecha_hito_e" id="fecha_hito_e" />
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="descr_hito" class="form-label">Descripción(*)</label>
                                <textarea name="descr_hito" id="descr_hito" class="form-control" rows="1"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row" id="cargando-9-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                              <h4 class="bx-flashing">Cargando...</h4>
                            </div>
                          </div>
                          <button type="submit" style="display: none;" id="submit-form-hito">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" onclick="limpiar_hito();"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_hito"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-plan -->

              </div>
            </div>

            <?php } else { $title_submodulo ='perfil'; $descripcion ='Perfil del PI'; $title_modulo = 'proyecto'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <script src="scripts/perfil_proyecto.js"></script>
          <!-- Quill Editor JS -->
          <script src="../assets/libs/quill/quill.min.js"></script>
          <!-- Internal Quill JS -->
          <script src="../assets/js/quill-editor.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
