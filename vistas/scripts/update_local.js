// Definición de un objeto global para las variables
var globalVars = {
  idusuario: null,
  idfta: null,
  idftb: null,
  idftc: null
};

// Función para inicializar las variables del objeto global desde localStorage
function initializeGlobalVariables() {
  var storedData = localStorage.getItem('nube_id_usuario');
  
  if (storedData) {
    var parsedData = JSON.parse(storedData);
    
    globalVars.idusuario = parsedData.idusuario;
    globalVars.idfta = parsedData.idfta;
    globalVars.idftb = parsedData.idftb;
    globalVars.idftc = parsedData.idftc;

    console.log(globalVars.idusuario, globalVars.idfta, globalVars.idftb, globalVars.idftc);

    // Establecer los valores de los selects desde localStorage
    if("#filtro_a" == ""){
      document.getElementById('filtro_a').value = globalVars.idfta;
      document.getElementById('filtro_b').value = globalVars.idftb;
      document.getElementById('filtro_c').value = globalVars.idftc;
    } else if ("#filtro_a1" == ""){
      document.getElementById('filtro_a1').value = globalVars.idfta;
      document.getElementById('filtro_b1').value = globalVars.idftb;
      document.getElementById('filtro_c1').value = globalVars.idftc;
    }
  } else {
    console.log('No se encontraron datos en localStorage');
  }
}



// Inicializar las variables globales al cargar el archivo
initializeGlobalVariables();
