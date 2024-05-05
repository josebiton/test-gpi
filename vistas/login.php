<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from htmlstream.com/front/landing-classic-consulting.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 May 2021 14:17:37 GMT -->
<head>
  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Language" content="es">

  <title> Login | Proyecto Integrador </title>

  <meta name="description" content="Gestor de Proyectos integradores | Universidad Peruana Unión - Tarapoto-Perú">
  <meta name="keywords" content="ingeniería de sistemas, UPeU, Proyectos integradores, tarapoto">
  <meta name="author" content="Gestor de Poryectos Integradores">  
  <meta name="robots" content="index, follow">
  <!-- FACEBOOK -->
  <meta property="og:title" content="Gestor de Poryectos Integradores - gestiona, almacena y analisa los proyectos integradores de Ing de Sistemas UPeU campus Tarapoto">
  <meta property="og:description" content="Gestor de Proyectos Integradores | Universidad Peruana Unión - Tarapoto-Perú">
  <meta property="og:image" content="assets/images/brand-logos/desktop-white.png">
  <meta property="og:url" content="">
  <!-- TWITTER -->
  <!-- <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@nombre_de_usuario_de_twitter"> -->
  <meta name="twitter:title" content="Gestor de Poryectos Integradores">
  <meta name="twitter:description" content="Gestor de Proyectos integradores | Universidad Peruana Unión - Tarapoto-Perú">
  <meta name="twitter:image" content="assets/images/brand-logos/desktop-white.png">

  <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "name": "Gestor de Poryectos Integradores",
      "url": "",
      "description": "Gestor de Proyectos integradores | Universidad Peruana Unión - Tarapoto-Perú"
    }
  </script>

  <link rel="canonical" href="">

  <!-- Favicon -->
  <link rel="icon" href="../assets/images/brand-logos/logotipo.png" type="image/x-icon">
  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
  <!-- CSS Implementing Plugins -->
  <link rel="stylesheet" href="../assets/assets_front/css/vendor.min.css">
  <!-- CSS Front Template -->
  <link rel="stylesheet" href="../assets/assets_front/css/theme.minc619.css?v=1.0">

  <!-- Sweetalerts CSS -->
  <link rel="stylesheet" href="../assets/libs/sweetalert2/sweetalert2.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../assets/libs/toastr/toastr.min.css">
  <!-- Mi stylo -->
  <link rel="stylesheet" href="../assets/css/style_new.css">

</head>

<body>

  <video autoplay muted loop playsinline style="position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; z-index: -100;">
    <source src="../assets/images/authentication/fond.mp4" type="video/mp4">
    Tu navegador no soporta el video HTML5.
  </video>

  <!-- ========== MAIN ========== -->
  <main id="content" role="main" style="z-index: 100; position: relative;">
    <!-- Hero Section -->
    <div>
      <div class="position-relative z-index-2">
        <!-- Content -->
        <div class="d-md-flex">
          <div class="container d-md-flex align-items-md-center min-vh-md-100 text-center space-3 space-top-md-4 space-top-lg-3">
            <div class="w-lg-85 mx-lg-auto">
              <!-- Info -->
              <div class="mt-5 mb-7">
                <h1 class="display-4 text-white mb-3">Bienvenido de nuevo</h1>
                <p class="lead text-white">Inicie sesión para administrar su proyecto.</p>
              </div>
              <!-- End Info -->

              <!-- Form -->
              <form class="w-lg-85 mx-lg-auto" method="post" id="frmAcceso" name="frmAcceso" autocomplet="off">
                <div class="card p-3 mb-5">
                  <div class="form-row input-group-borderless">
                    <div class="col-sm mb-2 mb-md-0">
                      <input type="text" class="form-control shadow-none"  id="logina" name="logina" placeholder="Usuario" aria-label="Name" required>
                    </div>
                    <div class="col-sm d-sm-none">
                      <hr class="my-0">
                    </div>
                    <div class="col-sm column-divider-sm mb-2 mb-md-0">
                      <input type="password" class="form-control shadow-none" name="clavea" id="clavea" placeholder="Contraseña" aria-label="Email" required>
                    </div>
                    <div class="col-md-auto">
                      <button type="submit" id="login-admin-btn" class="btn btn-block btn-primary btn-wide login-btn">Iniciar Sesión</button>
                    </div>
                  </div>
                </div>
              </form>
              <!-- End Form -->
            </div>
          </div>
        </div>
        <!-- End Content -->
      </div>

      <div class="d-lg-none position-absolute top-0 right-0 bottom-0 left-0 bg-img-hero" style="background-image: url(../assets/images/media/media-71.jpg);"></div>
    </div>
    <!-- End Hero Section -->    
    
  </main>
  <!-- ========== END MAIN ========== -->

  <!-- ========== SECONDARY CONTENTS ========== -->
  <!-- Page Preloader -->
  <div id="jsPreloader" class="page-preloader">
    <div class="page-preloader-content-centered">
      <div class="spinner-grow text-primary" role="status">
        <span class="sr-only">Cargando...</span>
      </div>
    </div>
  </div>
  <!-- End Page Preloader -->
  <!-- ========== END SECONDARY CONTENTS ========== -->

  <!-- Go to Top -->
  <a class="js-go-to go-to position-fixed" href="javascript:;" style="visibility: hidden;"
     data-hs-go-to-options='{
       "offsetTop": 700,
       "position": {
         "init": {
           "right": 15
         },
         "show": {
           "bottom": 15
         },
         "hide": {
           "bottom": -15
         }
       }
     }'>
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- End Go to Top -->


  <!-- JS Implementing Plugins -->
  <script src="../assets/assets_front/js/vendor.min.js"></script>

  <!-- JS Front -->
  <script src="../assets/assets_front/js/theme.min.js"></script>

  <!-- JS Plugins Init. -->
  <script>
    $(window).on('load', function () {
      // PAGE PRELOADER
      // =======================================================
      setTimeout(function() {
        $('#jsPreloader').fadeOut(500)
      }, 1000)
    });

    $(document).on('ready', function () {
      
      // INITIALIZATION OF VIDEO ON BACKGROUND
      // =======================================================
      $('.js-video-bg').each(function () {
        var videoBg = new HSVideoBg($(this)).init();
      });


      // INITIALIZATION OF FORM VALIDATION
      // =======================================================
      $('.js-validate').each(function () {
        var validation = $.HSCore.components.HSValidation.init($(this));
      });


      // INITIALIZATION OF SLICK CAROUSEL
      // =======================================================
      $('.js-slick-carousel').each(function() {
        var slickCarousel = $.HSCore.components.HSSlickCarousel.init($(this));
      });


      // INITIALIZATION OF GO TO
      // =======================================================
      $('.js-go-to').each(function () {
        var goTo = new HSGoTo($(this)).init();
      });
    });
  </script>

  <!-- sweetalert2 -->
  <script src="../assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="../assets/libs/toastr/toastr.min.js"></script>

  <script src="../assets/js/funcion_general.js"></script>
  <script src="../assets/js/funcion_crud.js"></script>  

  <script src="scripts/login.js"></script>  

  <!-- IE Support -->
  <script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="../assets/assets_front/vendor/babel-polyfill/dist/polyfill.js"><\/script>');
  </script>
</body>

<!-- Mirrored from htmlstream.com/front/landing-classic-consulting.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 May 2021 14:17:44 GMT -->
</html>