// ========================================== */
        // BUSCADOR MEJORADO PARA LETRAS Y NÚMEROS
        // ========================================== */
        function limpiarTexto(texto) {
            return texto.toLowerCase()
                        .normalize("NFD")
                        .replace(/[\u0300-\u036f]/g, "") // Elimina acentos para evitar conflictos
                        .replace(/\s+/g, " ")            // Normaliza espacios múltiples
                        .trim();
        }

        function filtrarEmpleados() {
            let input = document.getElementById("buscadorEmpleados");
            let filtro = limpiarTexto(input.value);
            let tabla = document.getElementById("tablaEmpleados");
            let filas = tabla.getElementsByTagName("tr");

            for (let i = 1; i < filas.length; i++) {
                let fila = filas[i];
                let celdas = fila.getElementsByTagName("td");
                let coincide = false;

                // Si el buscador está vacío, mostramos todas las filas
                if (filtro === "") {
                    fila.style.display = "";
                    continue;
                }

                // Recorremos las celdas de datos (saltando la columna 0 de acciones)
                for (let j = 1; j < celdas.length; j++) {
                    let celda = celdas[j];
                    if (celda) {
                        let contenido = limpiarTexto(celda.textContent || celda.innerText);
                        // Si el texto de la celda incluye lo que escribiste (letras o números)
                        if (contenido.indexOf(filtro) > -1) {
                            coincide = true;
                            break;
                        }
                    }
                }
                fila.style.display = coincide ? "" : "none";
            }
        }