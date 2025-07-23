<!-- Modal Éxito -->
<div id="modal-exito" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal()">&times;</span>
    <h2>✅ ¡Compra realizada!</h2>
    <p>Tu número de venta es: <span id="num-venta"></span></p>
    <button class="btn" onclick="cerrarModal()">Cerrar</button>
  </div>
</div>

<style>
.modal {
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 20px;
  border: 1px solid #888;
  max-width: 400px;
  text-align: center;
  border-radius: 8px;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  cursor: pointer;
}

.close:hover {
  color: #000;
}

.btn {
  display: inline-block;
  margin-top: 15px;
  padding: 8px 12px;
  background-color: #33b3d6;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}

.btn:hover {
  background-color: #195F89;
}
</style>

<script>
function mostrarModal(id_venta) {
    document.getElementById('num-venta').textContent = id_venta;
    document.getElementById('modal-exito').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modal-exito').style.display = 'none';
    window.location.reload(); // opcional, recarga para ver carrito vacío
}
</script>
