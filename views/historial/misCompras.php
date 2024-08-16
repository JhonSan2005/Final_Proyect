<div class="container mt-5">
    <h2>Mis Compras</h2>
    
    <?php if (empty($compras)): ?>
        <p>No has realizado ninguna compra aún.</p>
    <?php else: ?>
        <div class="d-flex justify-content-end mb-3">
            <input type="date" id="fechaInicio" class="form-control me-2">
            <input type="date" id="fechaFin" class="form-control me-2">
            <button id="ordenarFechas" class="btn btn-primary">Ordenar por Fecha</button>
        </div>
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
            <tbody id="tablaCompras">
                <?php foreach ($compras as $idFactura => $compra): ?>
                    <tr onclick="mostrarDetalles(<?= $idFactura ?>)">
                        <td><?= $idFactura ?></td>
                        <td><?= $compra['fecha_facturacion'] ?></td>
                        <td><?= isset($compra['descripcion']) ? $compra['descripcion'] : 'N/A' ?></td>
                        <td><?= $compra['impuesto'] ?>%</td>
                        <td><?= $compra['direccion_facturacion'] ?></td>
                        <td>Aprobado</td>
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
    let totalGeneral = 0;
    
    if (compra && compra.productos.length > 0) {
        detallesHtml += '<ul>';
        compra.productos.forEach(producto => {
            const totalProducto = producto.cantidad * producto.precio;
            totalGeneral += totalProducto;
            detallesHtml += `<li>${producto.nombre_producto} - ${producto.cantidad} unidades - $${producto.precio} cada uno - Total: $${totalProducto.toFixed(2)}</li>`;
        });
        detallesHtml += '</ul>';
        detallesHtml += `<p><strong>Total General: $${totalGeneral.toFixed(2)}</strong></p>`;
    } else {
        detallesHtml = "No se encontraron detalles.";
    }

    document.getElementById('contenidoDetalles').innerHTML = detallesHtml;
    
    var myModal = new bootstrap.Modal(document.getElementById('detallesModal'));
    myModal.show();
}

document.getElementById('ordenarFechas').addEventListener('click', function() {
    const tabla = document.getElementById('tablaCompras');
    const fechaInicio = new Date(document.getElementById('fechaInicio').value);
    const fechaFin = new Date(document.getElementById('fechaFin').value);

    if (isNaN(fechaInicio) || isNaN(fechaFin)) {
        alert('Por favor, selecciona ambas fechas.');
        return;
    }

    let filas = Array.from(tabla.querySelectorAll('tr'));
    let filasFiltradas = filas.filter(fila => {
        const fecha = new Date(fila.cells[1].innerText);
        return fecha >= fechaInicio && fecha <= fechaFin;
    });

    // Limpiar la tabla antes de mostrar las filas filtradas
    tabla.innerHTML = '';
    
    if (filasFiltradas.length === 0) {
        tabla.innerHTML = '<tr><td colspan="6">No hay compras en el rango de fechas seleccionado.</td></tr>';
    } else {
        filasFiltradas.forEach(fila => tabla.appendChild(fila));
    }
});
</script>
