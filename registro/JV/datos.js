document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.querySelector('#tabla-servidores tbody');
  if (!tbody) return;
  
  tbody.innerHTML = ''; // Limpiar la tabla

  // Datos directos de tus empleados
  const listaEmpleados = [
    ["997016031", "BAFA7504306X3", "BAFA750430MMCRRR15", "BARRERA FERNANDEZ ARACELI", "1975-04-30", "", "CASADA", "SAN CRISTOBAL HUICHOCHITLAN", "CALLE SONORA", "TOLUCA", "50010.0", "722 289 0102", "", "596135.0", "2019-06-01", "2019-06-01", "FEMENINO", "2026-08-14", "20703000000200S", "UNIDAD DE APOYO TÉCNICO ADMINISTRATIVO", "INGRESOS", "207000000005635", "SUTEYM", "GENERAL", "J0115232", "Jefa e de Área", "C100200312", "COODINACION GENERAL DE COMUNICACION SOCI"],
    ["943615192", "BECS750401AY4", "BECS750401MMCRSL00", "BERNAL CISNEROS SILVIA PATRICIA", "1975-04-01", "", "SOLTERA", "UNION", "ZAPOTECAS", "TOLUCA", "50040.0", "7291398225", "", "361519.0", "2016-09-01", "2016-09-01", "FEMENINO", "2026-08-15", "20703000000200S", "UNIDAD DE APOYO TÉCNICO ADMINISTRATIVO", "INGRESOS", "207000000005637", "SUTEYM", "GENERAL", "J0128202", "Jefa e B de Proyecto", "C100200312", "COODINACION GENERAL DE COMUNICACION SOCI"],
    ["954093220", "CARA6311304S9", "CARA631130HMCRSN03", "CARMONA RESENDIZ ANDRES", "1963-11-30", "", "CASADO", "SEMINARIO 1A SECCION", "LAGO BUSTILLOS", "TOLUCA", "50170.0", "7226116700", "", "409322.0", "2017-07-01", "2017-07-01", "MASCULINO", "2026-08-14", "20703000000200S", "UNIDAD DE APOYO TÉCNICO ADMINISTRATIVO", "INGRESOS", "207000000005626", "SUTEYM", "GENERAL", "J0128202", "Jefa e B de Proyecto", "C100200312", "COODINACION GENERAL DE COMUNICACION SOCI"],
    ["997697595", "CAPI880229AR1", "CAPI880229HVZSRS09", "CASTILLO PERDOMO ISAAC", "1988-02-29", "", "SOLTERO", "SAN PEDRO TOTOLTEPEC", "VICENTE GUERRERO", "TOLUCA", "50226.0", "7221234567", "", "554211.0", "2020-01-15", "2020-01-15", "MASCULINO", "2026-08-14", "20703000000200S", "UNIDAD DE APOYO TÉCNICO ADMINISTRATIVO", "INGRESOS", "207000000005628", "SUTEYM", "GENERAL", "J0128203", "Analista", "C100200312", "COODINACION GENERAL DE COMUNICACION SOCI"],
    ["998506360", "GIVS850903GKA", "GIVS850903MDFLLN01", "GIL VALENCIA SONIA", "1985-09-03", "", "CASADA", "LA TECNOLOGICA", "AV TECNOLOGICO", "TOLUCA", "50120.0", "7229876543", "", "482119.0", "2018-03-01", "2018-03-01", "FEMENINO", "2026-08-14", "20703000000200S", "UNIDAD DE APOYO TÉCNICO ADMINISTRATIVO", "INGRESOS", "207000000005630", "SUTEYM", "GENERAL", "J0128204", "Coordinadora", "C100200312", "COODINACION GENERAL DE COMUNICACION SOCI"]
  ];

  listaEmpleados.forEach((empleado) => {
    const tr = document.createElement('tr');
    const idEmp = empleado[0] || 'N/A';

    let htmlFila = `
      <td>
        <button class="btn-editar" onclick="editarFila(this, '${idEmp}')">✏️ Editar</button>
        <button class="btn-eliminar" onclick="eliminarFila(this, '${idEmp}')">🗑️ Eliminar</button>
      </td>
    `;

    empleado.forEach((valor) => {
      htmlFila += `<td>${valor}</td>`;
    });

    tr.innerHTML = htmlFila;
    tbody.appendChild(tr);
  });
});

function editarFila(boton, idEmpleado) {
  const fila = boton.parentNode.parentNode;
  const celdas = fila.querySelectorAll('td');

  if (boton.innerText.includes('Editar')) {
    for (let i = 2; i < celdas.length; i++) {
      celdas[i].contentEditable = "true";
      celdas[i].style.backgroundColor = "#fff3cd";
    }
    boton.innerText = "💾 Guardar";
    boton.style.backgroundColor = "#28a745";
  } else {
    alert("¡Modificado y guardado con éxito!");
    for (let i = 2; i < celdas.length; i++) {
      celdas[i].contentEditable = "false";
      celdas[i].style.backgroundColor = "";
    }
    boton.innerText = "✏️ Editar";
    boton.style.backgroundColor = "#007bff";
  }
}

function eliminarFila(boton, idEmpleado) {
  if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
    alert("Registro eliminado con éxito.");
    boton.parentNode.parentNode.remove();
  }
}