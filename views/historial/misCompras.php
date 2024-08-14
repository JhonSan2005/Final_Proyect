<div class="container mt-5">
    <h2>Mis Compras</h2>
    
    <?php if (empty($compras)): ?>
        <p>No has realizado ninguna compra aún.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Factura</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Impuesto</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $idFactura => $compra): ?>
                    <tr onclick="mostrarDetalles(<?= $idFactura ?>)">
                        <td><?= $idFactura ?></td>
                        <td><?= $compra['fecha_facturacion'] ?></td>
                        <td><?= isset($compra['descripcion']) ? $compra['descripcion'] : 'N/A' ?></td>
                        <td><?= $compra['impuesto'] ?>%</td>
                        <td><?= $compra['direccion_facturacion'] ?></td>
                        <td><?= isset($compra['estado']) ? $compra['estado'] : 'N/A' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Modal para mostrar detalles de la factura -->
<div id="detallesModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
                <!-- Aquí se llenarán los detalles de la factura -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarDetalles(idFactura) {
    const compras = <?= json_encode($compras) ?>;
    const compra = compras[idFactura];
    
    let detallesHtml = '';
    if (compra && compra.productos.length > 0) {
        detallesHtml += '<ul>';
        compra.productos.forEach(producto => {
            detallesHtml += `<li>${producto.nombre_producto} - ${producto.cantidad} unidades - $${producto.precio}</li>`;
        });
        detallesHtml += '</ul>';
    } else {
        detallesHtml = "No se encontraron detalles.";
    }

    document.getElementById('contenidoDetalles').innerHTML = detallesHtml;
    
    var myModal = new bootstrap.Modal(document.getElementById('detallesModal'));
    myModal.show();
}
</script>
