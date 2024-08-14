<div class="container-xl px-4 mt-4">
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="#">Actualizar Producto</a>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">
        <!-- Imagen del Producto -->
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Imagen del Producto</div>
                <div class="card-body text-center">
                    <img id="productImage" class="img-account-profile mb-2 img-thumbnail" 
                         src="<?php echo htmlspecialchars($producto['imagen_url'] ?? 'http://bootdey.com/img/Content/avatar/avatar1.png'); ?>" 
                         alt="Imagen del Producto">
                  
                    <div id="previewContainer" class="mb-3" style="display: none;">
                        <img id="previewImage" class="img-thumbnail" src="#" alt="Vista previa">
                        <button type="button" class="btn btn-danger mt-2" onclick="cancelUpload()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
  
        <!-- Detalles del Producto -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Detalles del Producto</div>
                <div class="card-body">
                    <form action="/products/actualizarProducto?id=<?php echo htmlspecialchars($producto['id_producto']); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto'] ?? ''); ?>">

                        <div class="mb-3">
                            <label class="small mb-1" for="nombre_producto">Nombre del Producto</label>
                            <input class="form-control" id="nombre_producto" name="nombre_producto" type="text" 
                                placeholder="Ingresa el nombre del producto" value="<?php echo htmlspecialchars($producto['nombre_producto'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="precio">Precio</label>
                            <input class="form-control" id="precio" name="precio" type="text" 
                                placeholder="Ingresa el precio" value="<?php echo htmlspecialchars($producto['precio'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="impuesto">Impuesto</label>
                            <input class="form-control" id="impuesto" name="impuesto" type="text" 
                                placeholder="Ingresa el impuesto" value="<?php echo htmlspecialchars($producto['impuesto'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="stock">Stock</label>
                            <input class="form-control" id="stock" name="stock" type="text" 
                                placeholder="Ingresa el stock" value="<?php echo htmlspecialchars($producto['stock'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="id_categoria">Categoría</label>
                            <select class="form-control" id="id_categoria" name="id_categoria">
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>"
                                        <?php echo ($categoria['id_categoria'] == ($producto['id_categoria'] ?? '')) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Ingresa la descripción"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="imagen_url">Imagen URL</label>
                            <input class="form-control" id="imagen_url" name="imagen_url" type="text" 
                                placeholder="URL de la imagen" value="<?php echo htmlspecialchars($producto['imagen_url'] ?? ''); ?>">
                        </div>

                        <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                        <!-- Botón para abrir el modal de eliminación -->
                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $producto['id_producto']; ?>">Eliminar Producto</button>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $producto['id_producto']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $producto['id_producto']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel<?php echo $producto['id_producto']; ?>">Confirmar Eliminación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar este producto?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="/admin/products?id=<?php echo $producto['id_producto']; ?>" method="POST">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function cancelUpload() {
        document.getElementById('previewContainer').style.display = 'none';
        document.getElementById('imagen_producto').value = '';
    }
</script>
