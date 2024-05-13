<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  date_default_timezone_set('America/Lima'); require "../config/funcion_general.php";
  session_start();
  if (isset($_SESSION["user_nombre"]) && $_SESSION["nivel_autoridad"] == "Nivel_1"){
    ?>
      <!DOCTYPE html>
      <html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="icon-overlay-close" loader="enable">

        <head>
          
          <?php $title_page = "Empresa"; include("template/head.php"); ?>    

        </head> 

        <body id="body-usuario" idusuario="<?php echo $_SESSION['idusuario']; ?>"  > 

          <?php include("template/switcher.php"); ?>
          <?php include("template/loader.php"); ?>

          <div class="page">
            <?php include("template/header.php") ?>
            <?php include("template/sidebar.php") ?>
            <?php if($_SESSION['empresa'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content ">
              <div class="container-fluid">

                <!-- Start::page-header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn-modal-effect btn btn-primary label-btn btn-agregar m-r-10px" onclick=""  > <i class="ri-user-add-line label-btn-icon me-2"></i>Editar </button>
                      <div>
                        <h5 class="fw-semibold fs-18 mb-0">Datos Generales de la empresa</h5>
                      </div>                
                    </div>
                  </div>
                  
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">LOGISTICA</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Empresa</li>
                      </ol>
                    </nav>
                  </div>
                </div>          
                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    
                    <div class="card custom-card ">                  
                      <div class="card-body">
                                            
                      </div>  
                      <div class="card-footer border-top-0">

                      </div>                
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->           
                </div>
                <!-- End::row-1 -->

              </div>
            </div>
            <!-- End::app-content -->
            <?php } else { $title_submodulo ='Usuario'; $descripcion ='Datos Generales de la Empresa!'; $title_modulo = 'Administracion'; include("403_error.php"); }?>     

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>
          <?php include("template/custom_switcherjs.php"); ?>    

          <!-- <script src="scripts/empresa.js"></script> -->
          <script> $(function () { $('[data-bs-toggle="tooltip"]').tooltip(); }); </script>

        
        </body>

      </html>
    <?php
  }else{
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));  
  }
  ob_end_flush();

?>
