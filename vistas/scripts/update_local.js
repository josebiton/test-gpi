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
    document.getElementById('filtro_a').value = globalVars.idfta;
    document.getElementById('filtro_b').value = globalVars.idftb;
    document.getElementById('filtro_c').value = globalVars.idftc;
  } else {
    console.log('No se encontraron datos en localStorage');
  }
}

// Función para actualizar localStorage con los nuevos valores y recargar la página
function filtrar_pi() {
  // Capturar los valores seleccionados en los selects
  var selectedCarrera = document.getElementById('filtro_a').value;
  var selectedSemestre = document.getElementById('filtro_b').value;
  var selectedEquipo = document.getElementById('filtro_c').value;

  // Actualizar las variables globales
  globalVars.idfta = selectedCarrera;
  globalVars.idftb = selectedSemestre;
  globalVars.idftc = selectedEquipo;

  // Mantener idusuario y actualizar localStorage
  localStorage.setItem('nube_id_usuario', JSON.stringify(globalVars));

  console.log('LocalStorage actualizado', globalVars);

  // Recargar la página para aplicar los cambios
  location.reload();
}

// Inicializar las variables globales al cargar el archivo
initializeGlobalVariables();
