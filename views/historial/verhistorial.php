<?php
$conexion = Conexion::conectar();

// Consulta con INNER JOIN para obtener los detalles de las ventas
$query = "
    SELECT v.id, v.id_factura, p.nombre_producto, p.precio, v.cantidad, f.fecha_facturacion
    FROM ventas v
    INNER JOIN productos p ON v.id_producto = p.id_producto
    INNER JOIN factura f ON v.id_factura = f.id
";
$result_ventas = $conexion->query($query);

if (!$result_ventas) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!-- Contenedor para la tabla de ventas -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-success rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-success">Detalles de Ventas</h2>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Venta</th>
                                    <th>ID Factura</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Fecha de Facturación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_ventas->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['id_factura']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                        <td><?php echo htmlspecialchars($row['precio']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fecha_facturacion']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        padding: 30px;
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .card-body {
        padding: 25px;
    }
    .table {
        margin-top: 20px;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: bold;
    }
    .table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.3s;
    }
    .table tbody tr:hover {
        background-color: #d4edda; /* Color success en hover */
    }
    .table tbody td {
        vertical-align: middle;
    }
</style>
