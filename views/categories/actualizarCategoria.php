<!-- Detalles del Producto -->
<div class="col-xl-8">
    <div class="card mb-4">
        <div class="card-header">Detalles del Producto</div>
        <div class="card-body">
            <form action="/categories/actualizarCategoria?id=<?php echo htmlspecialchars($categoria['id_categoria']); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($categoria['id_categoria'] ?? ''); ?>">

                <div class="mb-3">
                    <label class="small mb-1" for="nombre_categoria">Nombre de la Categoría</label>
                    <input class="form-control" id="nombre_categoria" name="nombre_categoria" type="text" 
                        placeholder="Ingresa el nombre de la categoría" value="<?php echo htmlspecialchars($categoria['nombre_categoria'] ?? ''); ?>">
                </div>

                <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                <!-- Botón para abrir el modal de eliminación -->
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo htmlspecialchars($producto['id_producto']); ?>">Eliminar Producto</button>
            </form>

            <!-- Modal -->
            <div class="modal fade" id="deleteModal<?php echo htmlspecialchars($producto['id_producto']); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo htmlspecialchars($producto['id_producto']); ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo htmlspecialchars($producto['id_producto']); ?>">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas eliminar este producto?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="/admin/categories?id=<?php echo htmlspecialchars($producto['id_producto']); ?>" method="POST">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
