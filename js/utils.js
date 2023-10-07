/**
 * Función que muestra un mensaje de alerta en la pantalla
 * @param {string} tipo - Tipo de alerta (error, ok)
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} operacion - Operación que se está realizando (crear, editar, borrar, consultar)
 */
function mostrarAlert(tipo, mensaje, operacion) {
  const tipos = {
    error: "danger",
    ok: "success",
  };

  const operaciones = {
    crear: "plus",
    editar: "pencil",
    borrar: "trash",
    consultar: "table",
  };

  const alertWrapper = document.getElementById("alert-place");
  alertWrapper.innerHTML += `
    <div class="alert alert-${tipos[tipo]} alert-dismissible" role="alert">
      <div><i class="fa-solid fa-${operaciones[operacion]} fs-6 me-3"></i> ${mensaje}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;

  // cerrar el alert tras 2 segundos con un fadeout
  const alert = bootstrap.Alert.getOrCreateInstance(".alert");
  setTimeout(() => {
    alert.close();
  }, 3000);
}

/**
 * Función que formatea un número a formato de moneda
 * @param {number} numero - Número a formatear
 * @returns {string} numeroFormateado
 */
function formatearCantidad(numero) {
  if (!numero) return "0.00";
  const numeroFormateado = parseFloat(numero).toFixed(2);
  const partes = numeroFormateado.toString().split(".");
  partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return partes.join(".");
}

/**
 * Función que formatea una fecha a formato dd/mm hh:mm
 * @param {string} fechaString - Fecha a formatear
 * @returns {string} fechaHoraFormateada
 */
function formatearFecha(fechaString) {
  const fechaHora = fechaString ? new Date(fechaString) : null;
  const fechaHoraFormateada = fechaHora
    ? `${fechaHora.getDate().toString().padStart(2, "0")}/${(
        fechaHora.getMonth() + 1
      )
        .toString()
        .padStart(2, "0")} ${fechaHora
        .getHours()
        .toString()
        .padStart(2, "0")}:${fechaHora
        .getMinutes()
        .toString()
        .padStart(2, "0")}`
    : "";

  return fechaHoraFormateada;
}

/**
 * Función que muestra un mensaje de alerta en la pantalla si hay parámetros en la URL
 */
function mostrarAlertaSiHayMensaje() {
  const params = new URLSearchParams(window.location.search);
  const ok = params.has("ok");
  const error = params.has("error");
  const mensaje = params.get("mensaje");
  const operacion = params.get("op");

  if (ok || error) {
    mostrarAlert(ok ? "ok" : "error", mensaje, operacion);
  }
}
