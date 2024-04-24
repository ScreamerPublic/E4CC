<?php
function obtenerFormulario(){
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar Pago</h5>
                <button type="button" class="btn-close" onclick="cerrarModal();" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-group" id="form-container"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarModal();">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="enviarFormulario();">Guardar</button>
              </div>
            </div>
        </div>
    <?php
}