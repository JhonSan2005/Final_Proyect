<!-- Detalles de la Categoría -->
<div class="col-xl-8">
    <div class="card mb-4">
        <div class="card-header">Detalles de la Categoría</div>
        <div class="card-body">
            <form action="/admin/actualizarCategoria?id=<?php echo htmlspecialchars($categoria['id_categoria']); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_categoria" value="<?php echo htmlspecialchars($categoria['id_categoria'] ?? ''); ?>">

                <div class="mb-3">
                    <label class="small mb-1" for="nombre_categoria">Nombre de la Categoría</label>
                    <input class="form-control" id="nombre_categoria" name="nombre_categoria" type="text" 
                        value="<?php echo htmlspecialchars($categoria['nombre_categoria'] ?? ''); ?>">
                </div>

                <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                <!-- Botón para abrir el modal de eliminación -->
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo htmlspecialchars($categoria['id_categoria']); ?>">Eliminar Categoría</button>
            </form>

            <!-- Modal -->
            <div class="modal fade" id="deleteModal<?php echo htmlspecialchars($categoria['id_categoria']); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo htmlspecialchars($categoria['id_categoria']); ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo htmlspecialchars($categoria['id_categoria']); ?>">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas eliminar esta categoría?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="/admin/eliminarCategoria?id=<?php echo htmlspecialchars($categoria['id_categoria']); ?>" method="POST">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
