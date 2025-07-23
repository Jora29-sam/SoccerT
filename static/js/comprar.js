document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formComprar");
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault(); // evita el POST tradicional
      try {
        const resp = await fetch('comprar.php', { method: 'POST' });
        const data = await resp.json();

        if (data.ok) {
          mostrarModal("¡Compra realizada! ID Venta: " + data.id_venta);
        } else {
          mostrarModal("Ocurrió un error: " + (data.error || "Desconocido"));
        }
      } catch (err) {
        mostrarModal("Error al conectar con el servidor.");
        console.error(err);
      }
    });
  }
});
